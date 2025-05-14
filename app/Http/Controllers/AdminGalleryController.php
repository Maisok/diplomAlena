<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AdminGalleryController extends Controller
{
    
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $photos = Photo::latest()->paginate(12);
        return view('admin.gallery.index', compact('photos'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'description' => 'nullable|string|max:500'
        ], [
            'image.max' => 'Максимальный размер изображения 5MB'
        ]);

        $image = $request->file('image');
        $filename = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('gallery', $filename, 'public');

        Photo::create([
            'title' => $validated['title'],
            'image_path' => $path,
            'description' => $validated['description'],
            'admin_id' => Auth::id()
        ]);

        return redirect()->route('gallery.index')
            ->with('success', 'Фото успешно добавлено в галерею');
    }

    public function edit(Photo $photo)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.gallery.edit', compact('photo'));
    }

    public function update(Request $request, Photo $photo)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'nullable|string|max:500'
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($photo->image_path);
            
            $image = $request->file('image');
            $filename = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('gallery', $filename, 'public');
            $validated['image_path'] = $path;
        }

        $photo->update($validated);

        return redirect()->route('gallery.index')
            ->with('success', 'Фото успешно обновлено');
    }

    public function destroy(Photo $photo)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();
        
        return redirect()->route('gallery.index')
            ->with('success', 'Фото успешно удалено');
    }
}