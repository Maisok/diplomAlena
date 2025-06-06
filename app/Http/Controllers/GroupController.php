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
    
        // Загружаем группу с преподавателями и детьми
        $groups = Group::with(['educators', 'children'])->paginate(10);
    
        return view('admin.groups.index', compact('groups'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
    
        // Получаем всех воспитателей и загружаем количество групп
        $educators = User::where('status', 'educator')
            ->withCount('groupsAsEducator')
            ->get();
    
        // Оставляем только тех, у кого НЕТ групп вообще
        $availableEducators = $educators->filter(fn($e) => $e->groups_as_educator_count === 0);
    
        return view('admin.groups.create', compact('availableEducators'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
    
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('groups', 'name'),
            ],
            'educators' => [
                'required',
                'array',
                'min:1',
                'max:2',
                function ($attribute, $value, $fail) {
                    // Получаем всех преподавателей по ID
                    $educators = User::whereIn('id', $value)->get();
    
                    foreach ($educators as $educator) {
                        $groupCount = Group::whereHas('educators', fn($q) =>
                            $q->where('users.id', $educator->id)
                        )->count();
    
                        if ($groupCount >= 1) {
                            $fail("Преподаватель {$educator->full_name} уже состоит в другой группе.");
                        }
                    }
                },
            ],
            'educators.*' => 'exists:users,id',
        ], [
            'name.unique' => 'Группа с таким названием уже существует.',
            'educators.required' => 'Выберите хотя бы одного воспитателя.',
            'educators.array' => 'Некорректный формат данных для воспитателей.',
            'educators.min' => 'Минимум один воспитатель.',
            'educators.max' => 'Максимум два воспитателя.',
        ]);
    
        $group = Group::create(['name' => $validated['name']]);
        $group->educators()->attach($validated['educators']);
    
        return redirect()->route('groups.index')
            ->with('success', 'Группа успешно создана.');
    }

    public function edit(Group $group)
{
    if (!auth()->user()->isAdmin()) {
        return redirect()->route('home');
    }

    // Все воспитатели + их количество групп
    $educators = User::where('status', 'educator')
        ->withCount('groupsAsEducator')
        ->get();

    // ID уже назначенных воспитателей (даже если у них есть другие группы — они всё равно остаются в списке)
    $assignedEducators = $group->educators->pluck('id')->toArray();

    // Фильтруем: оставляем только тех, у кого НЕТ групп ИЛИ они уже закреплены за этой группой
    $educators = $educators->filter(function ($educator) use ($assignedEducators) {
        return $educator->groups_as_educator_count === 0 || in_array($educator->id, $assignedEducators);
    });

    return view('admin.groups.edit', compact('group', 'educators', 'assignedEducators'));
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
                Rule::unique('groups', 'name')->ignore($group->id),
            ],
            'educators' => [
                'required',
                'array',
                'min:1',
                'max:2',
                function ($attribute, $value, $fail) use ($group) {
                    // Получаем преподавателей по ID
                    $educators = User::whereIn('id', $value)->get();
    
                    foreach ($educators as $educator) {
                        $groupCount = Group::whereHas('educators', function ($q) use ($educator, $group) {
                                $q->where('users.id', $educator->id);
                            })
                            ->where('id', '<>', $group->id)
                            ->count();
    
                        if ($groupCount >= 1) {
                            $fail("Преподаватель {$educator->full_name} уже состоит в другой группе.");
                        }
                    }
                },
            ],
            'educators.*' => 'exists:users,id',
        ], [
            'name.unique' => 'Группа с таким названием уже существует.',
            'educators.required' => 'Выберите хотя бы одного воспитателя.',
            'educators.array' => 'Некорректный формат данных для воспитателей.',
            'educators.min' => 'Минимум один воспитатель.',
            'educators.max' => 'Максимум два воспитателя.',
        ]);
    
        $group->update(['name' => $validated['name']]);
        $group->educators()->sync($validated['educators']);
    
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