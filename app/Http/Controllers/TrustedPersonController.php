<?php

namespace App\Http\Controllers;

use App\Models\TrustedPerson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TrustedPersonController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $search = $request->input('search');

        $trustedPeople = TrustedPerson::with('parent')
            ->when($search, function ($query, $search) {
                return $query->whereHas('parent', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                      ->orWhere('last_name', 'like', "%$search%");
                })->orWhere('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%");
            })
            ->paginate(10);

        $parents = User::where('status', 'parent')->get();

        return view('admin.trusted_people.index', compact('trustedPeople', 'search', 'parents'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $parents = User::where('status', 'parent')->get();
        return view('admin.trusted_people.create', compact('parents'));
    }

public function store(Request $request)
{
    if (!auth()->user()->isAdmin()) {
        return redirect()->route('home');
    }

    $validated = Validator::make($request->all(), [
        'parent_id' => 'required|exists:users,id',
        'last_name' => 'required|string|max:50',
        'first_name' => 'required|string|max:50',
        'patronymic' => 'nullable|string|max:50',
        'phone_number' => [
            'nullable',
            'string',
            'max:20',
            function ($attribute, $value, $fail) {
                if (!$value) return; // Пропустить, если номер не указан

                $existsInUsers = \App\Models\User::where('phone_number', $value)->exists();
                $existsInTrusted = \App\Models\TrustedPerson::where('phone_number', $value)->exists();

                if ($existsInUsers || $existsInTrusted) {
                    $fail('Этот номер телефона уже используется.');
                }
            },
        ],
    ], [
        'parent_id.required' => 'Пожалуйста, выберите родителя.',
        'parent_id.exists' => 'Выбранный родитель не существует.',
        'last_name.required' => 'Фамилия обязательна для заполнения.',
        'last_name.string' => 'Фамилия должна быть строкой.',
        'last_name.max' => 'Фамилия не должна превышать :max символов.',
        'first_name.required' => 'Имя обязательно для заполнения.',
        'first_name.string' => 'Имя должно быть строкой.',
        'first_name.max' => 'Имя не должно превышать :max символов.',
        'patronymic.string' => 'Отчество должно быть строкой.',
        'patronymic.max' => 'Отчество не должно превышать :max символов.',
        'phone_number.string' => 'Номер телефона должен быть строкой.',
        'phone_number.max' => 'Номер телефона слишком длинный.',
        'phone_number.unique' => 'Этот номер телефона уже используется.',
    ])->validate();

    TrustedPerson::create($validated);

    return redirect()->route('trusted-people.index')->with('success', 'Доверенное лицо успешно добавлено.');
}

    public function edit(TrustedPerson $trusted_person)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $parents = User::where('status', 'parent')->get();
        return view('admin.trusted_people.edit', compact('trusted_person', 'parents'));
    }

    public function update(Request $request, TrustedPerson $trusted_person)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
    
        $validated = Validator::make($request->all(), [
            'parent_id' => 'required|exists:users,id',
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'patronymic' => 'nullable|string|max:50',
            'phone_number' => [
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) use ($trusted_person) {
                    if (!$value) return;
    
                    $existsInUsers = \App\Models\User::where('phone_number', $value)->exists();
                    $existsInTrusted = \App\Models\TrustedPerson::where('phone_number', $value)
                        ->where('id', '!=', $trusted_person->id)
                        ->exists();
    
                    if ($existsInUsers || $existsInTrusted) {
                        $fail('Этот номер телефона уже используется.');
                    }
                },
            ],
        ], [
            'parent_id.required' => 'Пожалуйста, выберите родителя.',
            'parent_id.exists' => 'Выбранный родитель не существует.',
            'last_name.required' => 'Фамилия обязательна для заполнения.',
            'last_name.string' => 'Фамилия должна быть строкой.',
            'last_name.max' => 'Фамилия не должна превышать :max символов.',
            'first_name.required' => 'Имя обязательно для заполнения.',
            'first_name.string' => 'Имя должно быть строкой.',
            'first_name.max' => 'Имя не должно превышать :max символов.',
            'patronymic.string' => 'Отчество должно быть строкой.',
            'patronymic.max' => 'Отчество не должно превышать :max символов.',
            'phone_number.string' => 'Номер телефона должен быть строкой.',
            'phone_number.max' => 'Номер телефона слишком длинный.',
            'phone_number.unique' => 'Этот номер телефона уже используется.',
        ])->validate();
    
        $trusted_person->update($validated);
    
        return redirect()->route('trusted-people.index')->with('success', 'Данные обновлены.');
    }

    public function destroy(TrustedPerson $trusted_person)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $trusted_person->delete();
        return back()->with('success', 'Доверенное лицо удалено.');
    }
}