<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Carbon\Carbon;

class GroupScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Получаем начало текущей недели (понедельник)
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = $weekStart->copy()->addDays(4); // Пятница

        // Получаем ID выбранной группы из запроса
        $selectedGroupId = $request->input('group_id');

        // Получаем все группы с расписанием на текущей неделе
        $groupsQuery = Group::with(['scheduleItems' => function ($query) use ($weekStart, $weekEnd) {
            $query->whereBetween('date', [
                $weekStart->format('Y-m-d'),
                $weekEnd->format('Y-m-d')
            ])->orderBy('date')->orderBy('start_time');
        }]);

        // Если выбрана группа — фильтруем
        if ($selectedGroupId) {
            $groups = $groupsQuery->where('id', $selectedGroupId)->get();
        } else {
            $groups = $groupsQuery->get();
        }

        // Группируем расписание по дням недели (1=Пн, ..., 5=Пт)
        $groups->transform(function ($group) {
            if ($group->scheduleItems->isNotEmpty()) {
                $group->formatted_schedule = $group->scheduleItems->groupBy(function ($item) {
                    return Carbon::parse($item->date)->dayOfWeekIso; // 1-5
                });
            } else {
                $group->formatted_schedule = collect(); // пустая коллекция
            }
            return $group;
        });

        // Для отображения в select
        $allGroups = Group::all();

        return view('group_schedules.index', compact('groups', 'allGroups', 'selectedGroupId'));
    }
}