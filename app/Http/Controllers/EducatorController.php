<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EducatorController extends Controller
{
    public function index()
    {
        $parentGroups = auth()->user()->children()->with('group')->get()
            ->pluck('group.id')->unique()->filter();
            
        $educators = User::where('status', 'educator')
            ->whereHas('groups', function($query) use ($parentGroups) {
                $query->whereIn('id', $parentGroups);
            })
            ->with('groups.children')
            ->orderBy('last_name')
            ->get();

        return view('educators.index', compact('educators'));
    }
}