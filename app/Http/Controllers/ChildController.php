<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ChildController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $search = $request->input('search');
        $group_id = $request->input('group_id');
    
        $children = Child::query()
            ->with('group', 'parent')
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhere('patronymic', 'like', '%' . $search . '%');
                });
            })
            ->when($group_id, function ($query, $group_id) {
                return $query->where('group_id', $group_id);
            })
            ->get();
    
        $groups = Group::all(); // Добавляем все группы для выпадающего списка
    
        return view('admin.children.index', compact('children', 'groups'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $groups = Group::withCount('children')->get();
        $parents = User::where('status', 'parent')->get();
        
        return view('admin.children.create', compact('groups', 'parents'));
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
            'birth_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $birthDate = new \DateTime($value);
                    $today = new \DateTime();
                    $age = $today->diff($birthDate);
    
                    if ($age->y >= 8 || ($age->y * 12 + $age->m) < 18) {
                        $fail('Ребенку должно быть от 18 месяцев до 8 лет.');
                    }
                },
            ],
            'group_id' => [
                'required',
                'exists:groups,id',
                function ($attribute, $value, $fail) {
                    $group = Group::find($value);
                    
                    // Проверка на переполнение группы
                    if ($group && $group->children_count >= 15) {
                        $fail('В этой группе уже максимальное количество детей (15).');
                    }
    
                    // Получаем всех детей в группе
                    $existingChildren = $group->children;
    
                    if ($existingChildren->isNotEmpty()) {
                        // Получаем минимальную и максимальную дату рождения
                        $minBirthDate = $existingChildren->min('birth_date');
                        $maxBirthDate = $existingChildren->max('birth_date');
    
                        $newBirthDate = Carbon::parse(request()->input('birth_date'));
    
                        // Диапазон допустимых дат для нового ребёнка
                        $minAllowedDate = Carbon::parse($minBirthDate)->subYear();
                        $maxAllowedDate = Carbon::parse($maxBirthDate)->addYear();
    
                        if ($newBirthDate < $minAllowedDate || $newBirthDate > $maxAllowedDate) {
                            $fail('Нельзя добавить ребёнка в эту группу из-за большой разницы в возрасте.');
                        }
                    }
                },
            ],
            'parent_id' => 'required|exists:users,id',
        ], [
            'last_name.regex' => 'Фамилия может содержать только буквы и дефисы',
            'first_name.regex' => 'Имя может содержать только буквы и дефисы',
            'patronymic.regex' => 'Отчество может содержать только буквы и дефисы',
            'birth_date.date' => 'Введите корректную дату рождения',
            'group_id.exists' => 'Выбранная группа не существует',
            'parent_id.exists' => 'Выбранный родитель не существует',
        ]);
    
        Child::create($validated);
    
        return redirect()->route('children.index')
            ->with('success', 'Ребенок успешно добавлен.');
    }

    public function edit(Child $child)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $groups = Group::all();
        $parents = User::where('status', 'parent')->get();
        
        return view('admin.children.edit', compact('child', 'groups', 'parents'));
    }

    public function update(Request $request, Child $child)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $validated = $request->validate([
            'last_name' => 'required|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'first_name' => 'required|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'patronymic' => 'nullable|string|max:50|regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u',
            'birth_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $birthDate = new \DateTime($value);
                    $today = new \DateTime();
                    $age = $today->diff($birthDate);
                    
                    if ($age->y >= 8 || ($age->y * 12 + $age->m) < 18) {
                        $fail('Ребенку должно быть от 18 месяцев до 8 лет.');
                    }
                },
            ],
            'group_id' => 'required|exists:groups,id',
            'parent_id' => 'required|exists:users,id',
        ], [
            'last_name.regex' => 'Фамилия может содержать только буквы и дефисы',
            'first_name.regex' => 'Имя может содержать только буквы и дефисы',
            'patronymic.regex' => 'Отчество может содержать только буквы и дефисы',
            'birth_date.date' => 'Введите корректную дату рождения',
            'group_id.exists' => 'Выбранная группа не существует',
            'parent_id.exists' => 'Выбранный родитель не существует',
        ]);

        $child->update($validated);

        return redirect()->route('children.index')
            ->with('success', 'Данные ребенка успешно обновлены.');
    }

    public function destroy(Child $child)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
        $child->delete();

        return redirect()->route('children.index')
            ->with('success', 'Ребенок успешно удален.');
    }
}