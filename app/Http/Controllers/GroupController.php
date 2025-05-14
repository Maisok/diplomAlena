<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $groups = Group::with(['educator', 'children'])->paginate(10);
        return view('admin.groups.index', compact('groups'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $educators = User::where('status', 'educator')
            ->with('groups')
            ->get()
            ->filter(fn($educator) => $educator->groups->isEmpty());
            
        return view('admin.groups.create', compact('educators'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:groups,name',
            'educator_id' => [
                'required',
                'exists:users,id',
            
            ],
        ], [
            'name.max' => 'Название группы не должно превышать 100 символов',
            'name.unique' => 'Группа с таким названием уже существует',
            'educator_id.unique' => 'Этот воспитатель уже назначен в другую группу',
        ]);

        Group::create($validated);

        return redirect()->route('groups.index')
            ->with('success', 'Группа успешно создана.');
    }

    public function edit(Group $group)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $educators = User::where('status', 'educator')
            ->with('groups')
            ->get();
            
        return view('admin.groups.edit', compact('group', 'educators'));
    }

    public function update(Request $request, Group $group)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('groups', 'name')->ignore($group->id)
            ],
            'educator_id' => [
                'required',
                'exists:users,id',
                Rule::unique('groups', 'educator_id')->ignore($group->id)
            ],
        ], [
            'name.max' => 'Название группы не должно превышать 100 символов',
            'name.unique' => 'Группа с таким названием уже существует',
            'educator_id.unique' => 'Этот воспитатель уже назначен в другую группу',
        ]);

        $group->update($validated);

        return redirect()->route('groups.index')
            ->with('success', 'Группа успешно обновлена.');
    }

    public function destroy(Group $group)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        if ($group->children()->exists()) {
            return redirect()->route('groups.index')
                ->with('error', 'Невозможно удалить группу, так как в ней есть дети.');
        }

        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'Группа успешно удалена.');
    }
}