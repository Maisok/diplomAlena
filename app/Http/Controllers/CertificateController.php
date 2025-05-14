<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $certificates = Certificate::latest()->paginate(10);
        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.certificates.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'required|in:license,certificate',
        ], [
            'title.max' => 'Название не должно превышать 200 символов',
            'image.required' => 'Изображение обязательно',
            'image.image' => 'Файл должен быть изображением',
            'image.mimes' => 'Допустимые форматы: jpeg, png, jpg, gif, svg',
            'image.max' => 'Максимальный размер изображения 2MB',
            'type.in' => 'Неверный тип документа',
        ]);

        // Обработка изображения
        $image = $request->file('image');
        $filename = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('certificates', $filename, 'public');
        $validated['image'] = $path;

        $validated['admin_id'] = Auth::id();

        Certificate::create($validated);

        return redirect()->route('certificates.index')
            ->with('success', 'Сертификат успешно добавлен.');
    }

    public function edit(Certificate $certificate)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'required|in:license,certificate',
        ], [
            'title.max' => 'Название не должно превышать 200 символов',
            'image.image' => 'Файл должен быть изображением',
            'image.mimes' => 'Допустимые форматы: jpeg, png, jpg, gif, svg',
            'image.max' => 'Максимальный размер изображения 2MB',
            'type.in' => 'Неверный тип документа',
        ]);

        // Обновление изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            Storage::disk('public')->delete($certificate->image);

            $image = $request->file('image');
            $filename = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('certificates', $filename, 'public');
            $validated['image'] = $path;
        }

        $certificate->update($validated);

        return redirect()->route('certificates.index')
            ->with('success', 'Сертификат успешно обновлен.');
    }

    public function destroy(Certificate $certificate)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        // Удаляем изображение
        Storage::disk('public')->delete($certificate->image);

        $certificate->delete();

        return redirect()->route('certificates.index')
            ->with('success', 'Сертификат успешно удален.');
    }
}