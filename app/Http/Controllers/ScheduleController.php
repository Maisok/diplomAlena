<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\ScheduleItem;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        // Устанавливаем начало недели (понедельник)
        $weekStart = $request->input('week_start', Carbon::now()->startOfWeek()->toDateString());
        $weekStart = Carbon::parse($weekStart)->startOfWeek(); // Всегда понедельник
        
        // Ограничиваем навигацию
        $today = Carbon::now()->startOfDay();
        $currentWeekStart = $today->copy()->startOfWeek();
        $maxFutureWeek = $currentWeekStart->copy()->addWeeks(3); // Максимум +3 недели
        
        if ($weekStart->lt($currentWeekStart)) {
            $weekStart = $currentWeekStart->copy();
        } elseif ($weekStart->gt($maxFutureWeek)) {
            $weekStart = $maxFutureWeek->copy();
        }
        
        // Русские названия дней недели
        Carbon::setLocale('ru');
        
        $groups = Group::with(['scheduleItems' => function($query) use ($weekStart) {
            $query->whereBetween('date', [
                $weekStart->format('Y-m-d'),
                $weekStart->copy()->addDays(4)->format('Y-m-d') // Только до пятницы
            ])->orderBy('date')
                ->orderBy('start_time');
        }])->get();
        
        $categories = ActivityCategory::all();
        
        return view('schedules.index', compact(
            'groups', 
            'categories', 
            'weekStart', 
            'today',
            'maxFutureWeek'
        ));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'activity_category_id' => 'required|exists:activity_categories,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i'
        ], [
            'group_id.required' => 'Не выбрана группа',
            'group_id.exists' => 'Выбранная группа не существует',
            'activity_category_id.required' => 'Не выбрана категория',
            'activity_category_id.exists' => 'Выбранная категория не существует',
            'date.required' => 'Не указана дата',
            'date.date' => 'Некорректный формат даты',
            'start_time.required' => 'Не указано время начала',
            'start_time.date_format' => 'Некорректный формат времени'
        ]);
    
        $category = ActivityCategory::find($validated['activity_category_id']);
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($category->duration_minutes);
        $maxDate = Carbon::now()->startOfWeek()->addWeeks(3)->addDays(4);
        $selectedDate = Carbon::parse($validated['date']);
    
        // Проверка времени (7:00 - 19:00)
        if ($startTime->hour < 7 || $endTime->hour > 19) {
            return back()->withErrors(['start_time' => 'Мероприятия можно планировать только с 7:00 до 19:00']);
        }

        if ($selectedDate->gt($maxDate)) {
            return back()->withErrors(['date' => 'Нельзя планировать занятия более чем на 3 недели вперед']);
        }
    
        // Проверка уникальности категории в день
        if ($category->once_per_day) {
            $existing = ScheduleItem::where('activity_category_id', $category->id)
                ->where('date', $validated['date'])
                ->where('group_id', $validated['group_id'])
                ->exists();
                
            if ($existing) {
                return back()->withErrors(['activity_category_id' => 'Эта категория может быть только один раз в день для этой группы']);
            }
        }
        
        // Проверка для уникальных категорий (только в это же время)
        if ($category->unique_across_groups) {
            $conflicting = ScheduleItem::where('activity_category_id', $category->id)
                ->where('date', $validated['date'])
                ->where(function($query) use ($startTime, $endTime) {
                    $query->where(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime->format('H:i:s'))
                          ->where('end_time', '>', $startTime->format('H:i:s'));
                    });
                })
                ->exists();
                
            if ($conflicting) {
                return back()->withErrors(['activity_category_id' => 'Эта категория уже запланирована в это время для другой группы']);
            }
        }
        
        // Проверка пересечения времени для текущей группы
        $groupConflict = ScheduleItem::where('group_id', $validated['group_id'])
            ->where('date', $validated['date'])
            ->where(function($query) use ($startTime, $endTime) {
                $query->where(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime->format('H:i:s'))
                      ->where('end_time', '>', $startTime->format('H:i:s'));
                });
            })
            ->exists();
            
        if ($groupConflict) {
            return back()->withErrors(['start_time' => 'В это время у группы уже запланировано другое мероприятие']);
        }
    
        ScheduleItem::create([
            'group_id' => $validated['group_id'],
            'activity_category_id' => $validated['activity_category_id'],
            'date' => $validated['date'],
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s')
        ]);
    
        return back()->with('success', 'Мероприятие успешно добавлено');
    }

    public function destroy(ScheduleItem $scheduleItem)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $scheduleItem->delete();
        return back()->with('success', 'Мероприятие удалено');
    }
}