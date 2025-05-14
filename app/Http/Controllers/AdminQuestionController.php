<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class AdminQuestionController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $unanswered = Question::unanswered()->latest()->get();
        $answered = Question::whereNotNull('answer')->latest()->paginate(10);

        return view('admin.questions.index', compact('unanswered', 'answered'));
    }

    public function show(Question $question)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $userData = $question->user ? [
            'name' => $question->user->full_name,
            'email' => $question->user->email,
            'phone' => $question->user->phone_number
        ] : null;

        return view('admin.questions.show', compact('question', 'userData'));
    }

    public function answer(Request $request, Question $question)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $request->validate([
            'answer' => 'required|string|max:1000',
            'publish' => 'sometimes|boolean' 
        ]);
    
        $question->answer = $request->answer;
        $question->is_published = $request->boolean('publish', false); 
        $question->answered_by = auth()->id();
        $question->answered_at = now();
        $question->save();
    
        return redirect()->route('admin.questions.index')->with('success', 'Ответ сохранен');
    }

    public function destroy(Question $question)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $question->delete();
        return back()->with('success', 'Вопрос удален');
    }
}