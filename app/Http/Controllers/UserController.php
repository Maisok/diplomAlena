<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $search = $request->input('search');
    
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhere('patronymic', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10); 
    
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'last_name' => 'required|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'first_name' => 'required|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'patronymic' => 'nullable|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'status' => 'required|in:parent,admin,educator',
            'phone_number' => 'required|string|max:20|regex:/^8 \d{3} \d{3} \d{2} \d{2}$/|unique:users,phone_number',
            'email' => 'required|email|max:100|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:users,email',
            'login' => 'required|string|max:5|min:5|unique:users,login',
            'password' => 'required|string|min:8|max:255',
        ], [
            'last_name.regex' => 'Фамилия может содержать только буквы и дефисы',
            'first_name.regex' => 'Имя может содержать только буквы и дефисы',
            'patronymic.regex' => 'Отчество может содержать только буквы и дефисы',
            'phone_number.regex' => 'Телефон должен быть в формате: 8 999 123 45 67',
            'email.regex' => 'Введите корректный email адрес',
            'login.max' => 'Логин не должен превышать 50 символов',
            'password.max' => 'Пароль не должен превышать 255 символов',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Пользователь успешно создан.');
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'last_name' => 'required|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'first_name' => 'required|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'patronymic' => 'nullable|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'status' => 'required|in:parent,admin,educator',
            'phone_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^8 \d{3} \d{3} \d{2} \d{2}$/',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                Rule::unique('users')->ignore($user->id)
            ],
            'login' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|max:255',
        ], [
            'last_name.regex' => 'Фамилия может содержать только буквы и дефисы',
            'first_name.regex' => 'Имя может содержать только буквы и дефисы',
            'patronymic.regex' => 'Отчество может содержать только буквы и дефисы',
            'phone_number.regex' => 'Телефон должен быть в формате: 8 999 123 45 67',
            'email.regex' => 'Введите корректный email адрес',
            'login.max' => 'Логин не должен превышать 50 символов',
            'password.max' => 'Пароль не должен превышать 255 символов',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Пользователь успешно обновлен.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        if ($user->status === 'educator' && $user->groups()->exists()) {
            return redirect()->route('users.index')->with('error', 'Невозможно удалить воспитателя, так как у него есть группы.');
        }
    
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Пользователь успешно удален.');
    }
}