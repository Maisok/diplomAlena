<?php

namespace App\Http\Controllers;

use App\Models\ActivityCategory;
use App\Models\ScheduleItem;
use Illuminate\Http\Request;

class ActivityCategoryController extends Controller
{
    public function index()
    {

        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $categories = ActivityCategory::all();
        return view('activity_categories.index', compact('categories'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('activity_categories.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'duration_minutes' => 'required|integer|min:10|max:180',
            'once_per_day' => 'sometimes|boolean',
            'unique_across_groups' => 'sometimes|boolean'
        ], [
            'duration_minutes.min' => 'Длительность должна быть не менее 10 минут',
            'duration_minutes.max' => 'Длительность должна быть не более 180 минут'
        ]);
    
        $validated['once_per_day'] = $request->has('once_per_day');
        $validated['unique_across_groups'] = $request->has('unique_across_groups');
    
        ActivityCategory::create($validated);
    
        return redirect()->route('activity-categories.index')
            ->with('success', 'Категория успешно создана');
    }

    public function edit(ActivityCategory $activityCategory)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $isUsed = ScheduleItem::where('activity_category_id', $activityCategory->id)->exists();
        return view('activity_categories.edit', compact('activityCategory', 'isUsed'));
    }

    public function update(Request $request, ActivityCategory $activityCategory)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        if (ScheduleItem::where('activity_category_id', $activityCategory->id)->exists()) {
            return back()->with('error', 'Нельзя редактировать категорию, так как она используется в расписании');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'duration_minutes' => 'required|integer|min:10|max:180',
            'once_per_day' => 'sometimes|boolean',
            'unique_across_groups' => 'sometimes|boolean'
        ], [
            'duration_minutes.min' => 'Длительность должна быть не менее 10 минут',
            'duration_minutes.max' => 'Длительность должна быть не более 180 минут'
        ]);
    
        $validated['once_per_day'] = $request->has('once_per_day');
        $validated['unique_across_groups'] = $request->has('unique_across_groups');
    
        $activityCategory->update($validated);
    
        return redirect()->route('activity-categories.index')
            ->with('success', 'Категория успешно обновлена');
    }

    public function destroy(ActivityCategory $activityCategory)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        if (ScheduleItem::where('activity_category_id', $activityCategory->id)->exists()) {
            return redirect()->route('activity-categories.index')
                ->with('error', 'Нельзя удалить категорию, так как она используется в расписании');
        }
    
        $activityCategory->delete();
        return redirect()->route('activity-categories.index')
            ->with('success', 'Категория успешно удалена');
    }
}