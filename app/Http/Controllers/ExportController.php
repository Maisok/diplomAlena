<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Group;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ChildrenExport;
use App\Models\ScheduleItem;
use App\Exports\GroupScheduleExport;

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
        return view('admin.export', compact('groups'));
    }

    // Экспорт расписания в Excel
    public function exportSchedule(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $group = Group::findOrFail($request->group_id);
        
        return Excel::download(
            new GroupScheduleExport($group), 
            'schedule_group_' . $group->id . '.xlsx'
        );
    }
}