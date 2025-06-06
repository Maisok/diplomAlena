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
    
        $groups = Group::with(['children' => function($query) {
            $query->orderBy('birth_date');
        }])->get();
    
        $parents = User::where('status', 'parent')->get();
    
        return view('admin.children.create', compact('groups', 'parents'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }
    
        $validated = $request->validate([
            'last_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u'
            ],
            'first_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u'
            ],
            'patronymic' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[а-яА-ЯёЁa-zA-Z\- ]+$/u'
            ],
            'birth_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $birthDate = Carbon::parse($value);
                    $ageInYears = $birthDate->diffInYears(Carbon::now());
    
                    if ($ageInYears >= 8 || $birthDate->diffInMonths(Carbon::now()) < 18) {
                        $fail('Ребенку должно быть от 18 месяцев до 8 лет.');
                    }
                },
            ],
            'group_id' => [
                'required',
                'exists:groups,id',
                function ($attribute, $value, $fail) {
                    $group = Group::with(['children' => function($query) {
                        $query->oldest('created_at'); // Загружаем детей по порядку добавления
                    }])->find($value);
    
                    // Проверка вместимости
                    if ($group && $group->children_count >= 15) {
                        $fail('В этой группе уже максимальное количество детей (15).');
                    }
    
                    $newBirthDate = Carbon::parse(request()->input('birth_date'));
    
                    if ($group->children->isNotEmpty()) {
                        // Берём самого первого ребёнка (по времени создания)
                        $firstChild = $group->children->first();
                        $firstBirthDate = Carbon::parse($firstChild->birth_date);
    
                        // Диапазон ±6 месяцев от первого ребёнка
                        $minAllowed = $firstBirthDate->copy()->subMonths(6);
                        $maxAllowed = $firstBirthDate->copy()->addMonths(6);
    
                        if ($newBirthDate < $minAllowed || $newBirthDate > $maxAllowed) {
                            $fail("Нельзя добавить ребёнка в эту группу из-за большой разницы в возрасте.");
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
    
        $groups = Group::with(['children' => function($query) {
            $query->orderBy('birth_date');
        }])->get();
    
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
                function ($attribute, $value, $fail) use ($request, $child) { // ← Добавлен $request в use
                    $birthDate = Carbon::parse($value);
                    $ageInYears = $birthDate->diffInYears(Carbon::now());
    
                    if ($ageInYears >= 8 || $birthDate->diffInMonths(Carbon::now()) < 18) {
                        $fail('Ребенку должно быть от 18 месяцев до 8 лет.');
                    }
    
                    $currentGroupId = $child->group_id;
                    $newGroupId = $request->input('group_id'); // Теперь доступ есть
    
                    if ($currentGroupId != $newGroupId) {
                        $group = Group::with(['children' => function($query) {
                            $query->oldest('created_at');
                        }])->find($newGroupId);
    
                        if ($group && $group->children->isNotEmpty()) {
                            $firstBirthDate = Carbon::parse($group->children->first()->birth_date);
                            $minAllowed = $firstBirthDate->copy()->subMonths(6);
                            $maxAllowed = $firstBirthDate->copy()->addMonths(6);
    
                            if ($birthDate < $minAllowed || $birthDate > $maxAllowed) {
                                $fail("");
                            }
                        }
                    }
                },
            ],
            'group_id' => [
                'required',
                'exists:groups,id',
                function ($attribute, $value, $fail) use ($child, $request) { // ← тоже добавляем $request сюда
                    $newGroupId = $value;
                    $oldGroupId = $child->group_id;
    
                    if ($newGroupId == $oldGroupId) {
                        return; // Группа не менялась — ничего не проверяем
                    }
    
                    $group = Group::with(['children' => function($query) {
                        $query->oldest('created_at');
                    }])->find($newGroupId);
    
                    if ($group && $group->children_count >= 15) {
                        $fail('В этой группе уже максимальное количество детей (15).');
                    }
    
                    if ($group->children->isNotEmpty()) {
                        $firstBirthDate = Carbon::parse($group->children->first()->birth_date);
                        $minAllowed = $firstBirthDate->copy()->subMonths(6);
                        $maxAllowed = $firstBirthDate->copy()->addMonths(6);
    
                        $childBirthDate = Carbon::parse($child->birth_date);
    
                        if ($childBirthDate < $minAllowed || $childBirthDate > $maxAllowed) {
                            $fail("Нельзя переместить ребёнка в эту группу из-за большой разницы в возрасте.");
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