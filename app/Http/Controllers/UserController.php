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
        $status = $request->input('status');
    
        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                      ->orWhere('last_name', 'like', "%$search%")
                      ->orWhere('patronymic', 'like', "%$search%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
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
            'status' => 'required|in:parent,educator,nanny',
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
    
        // Проверяем, если пользователь родитель и имеет детей
        if ($user->isParent() && $user->children->isNotEmpty()) {
            // Если статус меняется и не остался "parent"
            if ($request->filled('status') && $request->input('status') !== 'parent') {
                return back()
                    ->withErrors(['status' => 'Невозможно изменить статус: у родителя есть дети.'])
                    ->withInput();
            }
        }
    
        // Теперь проводим валидацию
        $validated = $request->validate([
            'last_name' => 'required|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'first_name' => 'required|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'patronymic' => 'nullable|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'status' => 'required|in:parent,educator,nanny',
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
            'password.min' => 'Пароль не должен быть менее 8 символов',
            'password.max' => 'Пароль не должен превышать 255 символов',
        ]);
    
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
    
        $user->update($validated);
    
        return redirect()->route('users.index')
            ->with('success', 'Пользователь успешно обновлен.');
    }
    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
    
        // Проверка для воспитателя
        if ($user->isEducator() && $user->groups()->exists()) {
            return redirect()->route('users.index')->with('error', 'Невозможно удалить воспитателя, так как у него есть группы.');
        }
    
        // Проверка для няни
        if ($user->status === 'nanny' && $user->groupsAsNanny()->exists()) {
            return redirect()->route('users.index')->with('error', 'Невозможно удалить няню, так как она закреплена за группами.');
        }
    
        // Проверка для родителя
        if ($user->isParent() && $user->children()->exists()) {
            return redirect()->route('users.index')->with('error', 'Невозможно удалить родителя, так как у него есть дети.');
        }
    
        // Если все проверки пройдены — удаляем
        $user->delete();
    
        return redirect()->route('users.index')->with('success', 'Пользователь успешно удален.');
    }
}