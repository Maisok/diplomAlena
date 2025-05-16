<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $news = News::latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:600',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'title.max' => 'Заголовок не должен превышать 200 символов',
            'description.required' => 'Описание новости обязательно',
            'image.image' => 'Файл должен быть изображением',
            'image.mimes' => 'Допустимые форматы: jpeg, png, jpg, gif, svg',
            'image.max' => 'Максимальный размер изображения 2MB',
        ]);

        // Обработка изображения
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('news_images', $filename, 'public');
            $validated['image'] = $path;
        }

        $validated['admin_id'] = Auth::id();

        $news = News::create($validated);

        // Отправка в Telegram
        $this->sendToTelegram($news);

        return redirect()->route('news.index')
            ->with('success', 'Новость успешно добавлена.');
    }

    public function edit(News $news)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {   
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:600',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'title.max' => 'Заголовок не должен превышать 200 символов',
            'description.required' => 'Описание новости обязательно',
            'image.image' => 'Файл должен быть изображением',
            'image.mimes' => 'Допустимые форматы: jpeg, png, jpg, gif, svg',
            'image.max' => 'Максимальный размер изображения 2MB',
        ]);

        // Обновление изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            $image = $request->file('image');
            $filename = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('news_images', $filename, 'public');
            $validated['image'] = $path;
        }

        $news->update($validated);

        return redirect()->route('news.index')
            ->with('success', 'Новость успешно обновлена.');
    }

    public function destroy(News $news)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        // Удаляем изображение
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'Новость успешно удалена.');
    }

    public function show(News $news)
    {
        
        $newsItems = News::latest()->paginate(10);
        return view('news', compact('newsItems'));
    }

    public function list()
    {
        $news = News::latest()->paginate(5);
        return view('news.index', compact('news'));
    }

    private function sendToTelegram(News $news)
{
    try {
        $botToken = '7651468520:AAGBmLjvDVMG9aB6FuP7aT8e63sEoorXlBE';
        $chatId = '-1002196805641';
        
        $message = "📢 <b>Новая новость!</b>\n\n".
                   "<b>{$news->title}</b>\n\n".
                   "{$news->description}\n\n".
                   "<a href=\"".url('/news/'.$news->id)."\">Читать полностью</a>";

        // Если есть изображение
        if ($news->image) {
            $imagePath = storage_path('app/public/'.$news->image);
            
            if (!file_exists($imagePath)) {
                throw new \Exception("Image file not found: {$imagePath}");
            }

            // Отправка фото с подписью (используем sendPhoto)
            $response = Http::withoutVerifying()
                ->attach('photo', fopen($imagePath, 'r'))
                ->post("https://api.telegram.org/bot{$botToken}/sendPhoto", [
                    'chat_id' => $chatId,
                    'caption' => $message,
                    'parse_mode' => 'HTML'
                ]);
        } else {
            // Отправка только текста (используем sendMessage)
            $response = Http::withoutVerifying()
                ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true
                ]);
        }

        if (!$response->successful()) {
            \Log::error('Telegram API Error', [
                'response' => $response->json(),
                'status' => $response->status(),
                'news_id' => $news->id
            ]);
            throw new \Exception($response->body());
        }

    } catch (\Exception $e) {
        \Log::error('Telegram sending error', [
            'error' => $e->getMessage(),
            'news_id' => $news->id,
            'trace' => $e->getTraceAsString()
        ]);
    }
}
}