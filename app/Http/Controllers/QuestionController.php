<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    // Страница создания вопроса
    public function create()
    {
        return view('questions.create');
    }

    // Сохранение вопроса
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        Question::create([
            'question' => $request->question,
            'user_id' => Auth::id() // null для неавторизованных
        ]);

        return redirect()->route('questions.all')
            ->with('success', 'Ваш вопрос отправлен на модерацию. После проверки администратором он будет опубликован.');
    }



    public function all()
    {
        $questions = Question::where('is_published', true)
            ->whereNotNull('answer')
            ->latest()
            ->paginate(10);

        return view('questions.all', compact('questions'));
    }
}