<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Carbon\Carbon;

class GroupScheduleController extends Controller
{
    public function index()
    {
        // Загружаем все группы с расписанием и категориями занятий
        $groups = Group::with(['scheduleItems.activityCategory'])->get();

        // Группируем расписание по дням недели для каждой группы
        $groups->transform(function ($group) {
            if ($group->scheduleItems->isNotEmpty()) {
                $group->formatted_schedule = $group->scheduleItems->groupBy(function ($item) {
                    $date = is_string($item->date) ? Carbon::parse($item->date) : $item->date;
                    return $date->dayOfWeekIso; // 1=Пн, ..., 5=Пт
                });
            } else {
                $group->formatted_schedule = collect();
            }

            return $group;
        });

        return view('group_schedules.index', compact('groups'));
    }
}