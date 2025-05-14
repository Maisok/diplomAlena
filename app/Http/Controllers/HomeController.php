<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Photo;
use App\Models\Question;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = News::latest()->take(3)->get();
        $questions = Question::published()->latest()->get();
        $photos = Photo::latest()->take(7)->get();
        
        return view('welcome', compact('latestNews', 'questions', 'photos'));
    }
}