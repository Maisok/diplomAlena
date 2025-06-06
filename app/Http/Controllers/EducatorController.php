<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EducatorController extends Controller
{
    public function index()
    {
        // Получаем ID групп детей текущего пользователя (родителя)
        $parentGroups = auth()->user()->children()
            ->with('group')
            ->get()
            ->pluck('group.id')
            ->filter()
            ->unique();

        if ($parentGroups->isEmpty()) {
            return view('educators.index', ['educators' => collect()]);
        }

        // Получаем воспитателей, которые работают в этих группах
        $educators = User::where('status', 'educator')
            ->whereHas('groups', function ($query) use ($parentGroups) {
                $query->whereIn('groups.id', $parentGroups); // Уточнили: groups.id
            })
            ->with(['groups' => function ($query) use ($parentGroups) {
                $query->whereIn('groups.id', $parentGroups);
            }, 'groups.children'])
            ->orderBy('last_name')
            ->get();

        return view('educators.index', compact('educators'));
    }
}