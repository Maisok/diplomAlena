<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $unanswered = Question::unanswered()->latest()->get();
        $answered = Question::whereNotNull('answer')->latest()->paginate(10);

        return view('admin.questions.index', compact('unanswered', 'answered'));
    }

    public function show(Question $question)
    {
        // Для админа показываем дополнительную информацию
        $userData = $question->user ? [
            'name' => $question->user->full_name,
            'email' => $question->user->email,
            'phone' => $question->user->phone_number
        ] : null;

        return view('admin.questions.show', compact('question', 'userData'));
    }

    public function answer(Request $request, Question $question)
    {
        $request->validate([
            'answer' => 'required|string',
            'publish' => 'boolean'
        ]);

        $question->update([
            'answer' => $request->answer,
            'is_published' => $request->publish,
            'answered_by' => auth()->id(),
            'answered_at' => now()
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Ответ сохранен');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Вопрос удален');
    }
}