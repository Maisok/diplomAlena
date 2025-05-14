<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleItem;
use App\Models\ActivityCategory;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $data = ['user' => $user];

        if ($user->isAdmin()) {
            $data = array_merge($data, $this->getAdminData());
        }

        if ($user->isEducator()) {
            $data = array_merge($data, $this->getEducatorData($user));
        }

        if ($user->isParent()) {
            $data = array_merge($data, $this->getParentData($user));
        }

        return view('profile', $data);
    }

    protected function getAdminData()
    {
        return [
            'is_admin' => true,
            'stats' => [
                'total_users' => \App\Models\User::count(),
                'total_children' => \App\Models\Child::count(),
                'total_groups' => \App\Models\Group::count(),
            ],
        ];
    }

    protected function getEducatorData($user)
    {
        $group = $user->groups->first();
        $children = $group ? $group->children()->orderBy('last_name')->get() : collect();

        return [
            'is_educator' => true,
            'group' => $group,
            'children' => $children,
            'schedule_items' => $group ? $this->getGroupSchedule($group) : [],
            'activity_categories' => ActivityCategory::all()->keyBy('id')
        ];
    }

    protected function getParentData($user)
    {
        $children = $user->children()->with('group')->orderBy('last_name')->get();
        $childrenByGroup = $children->groupBy('group_id');
        
        $groupsData = [];
        foreach ($childrenByGroup as $groupId => $groupChildren) {
            $group = $groupChildren->first()->group;
            $groupsData[] = [
                'group' => $group,
                'children' => $groupChildren,
                'schedule' => $this->getGroupSchedule($group)
            ];
        }

        return [
            'is_parent' => true,
            'children' => $children,
            'groups_data' => $groupsData,
            'upcoming_events' => $this->getParentEvents($user)
        ];
    }

    protected function getGroupSchedule($group)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        return ScheduleItem::with('activityCategory')
            ->where('group_id', $group->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function($item) {
                $date = is_string($item->date) ? Carbon::parse($item->date) : $item->date;
                return $date->dayOfWeekIso;
            });
    }

    protected function getParentEvents($user)
    {
        $childrenIds = $user->children()->pluck('id');
        
    }
}