<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Group;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ChildrenExport;
use App\Models\ScheduleItem;
use App\Exports\GroupScheduleExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ExportController extends Controller
{
    public function showExportForm()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $groups = Group::all();
        return view('admin.export.form', compact('groups'));
    }

    public function exportChildren(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $group_id = $request->input('group_id');
        return Excel::download(new ChildrenExport($group_id), 'children.xlsx');
    }

    // Показываем форму выбора группы для экспорта
    public function showScheduleExportForm()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
    
        $groups = Group::all();
    
        // Устанавливаем текущую дату
        $today = Carbon::now();
        
        // Минимальная и максимальная недели
        $minDate = $today; // прошлая неделя (можно выбрать)
        $maxDate = $today->copy()->addMonth(); // +1 месяц вперед
    
        // Генерируем список недель
        $weeks = collect();
    
        $current = $minDate->copy()->startOfWeek();
        while ($current->lte($maxDate)) {
            $weekStart = $current->copy();
            $weekEnd = $current->copy()->endOfWeek();
    
            $weeks->push([
                'start' => $weekStart,
                'end' => $weekEnd,
                'label' => $weekStart->format('d.m') . ' – ' . $weekEnd->format('d.m.Y'),
                'value' => $weekStart->format('Y-m-d'), // значение для select
            ]);
    
            $current->addWeek();
        }
    
        return view('admin.export', compact('groups', 'weeks'));
    }

    // Экспорт расписания в Excel
    public function exportSchedule(Request $request)
{
    if (!auth()->user()->isAdmin()) {
        return redirect()->route('home');
    }

    // Валидация формы
    $validator = Validator::make($request->all(), [
        'group_id' => 'required|exists:groups,id',
        'week_start' => 'required|date',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator);
    }

    $validated = $validator->validate();

    // Парсим начало недели
    $weekStart = \Carbon\Carbon::parse($validated['week_start'])->startOfWeek();
    $weekEnd = $weekStart->copy()->endOfWeek();

    // Ограничиваем диапазон: -1 неделя до +1 месяца
    $minDate = \Carbon\Carbon::now()->subWeek()->startOfWeek();
    $maxDate = \Carbon\Carbon::now()->addMonth()->startOfWeek();

    if ($weekStart->lt($minDate) || $weekStart->gt($maxDate)) {
        return back()->withErrors(['week_start' => 'Выбранная неделя вне допустимого диапазона']);
    }

    $group = Group::findOrFail($validated['group_id']);

    // Фильтруем расписание по дате
    return Excel::download(
        new GroupScheduleExport($group, $weekStart, $weekEnd), 
        "schedule_group_{$group->id}_{$weekStart->format('d.m.Y')}.xlsx"
    );
}
}