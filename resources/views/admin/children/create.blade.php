<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить ребенка - 7 Звезд</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; }
        .gradient-text { background: linear-gradient(45deg, #4A3F9B, #D32F2F); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .gradient-bg { background: linear-gradient(45deg, #4A3F9B, #D32F2F); }
        .form-input { transition: all 0.2s ease; }
        .form-input:focus { border-color: #4A3F9B; box-shadow: 0 0 0 2px rgba(74, 63, 155, 0.2); }
    </style>
</head>
<body class="bg-gray-100">
    <x-header/>

    <main class="flex-grow">
        <div class="bg-gradient-to-r from-purple-100 to-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-3xl font-bold gradient-text mb-2">Добавить ребенка</h1>
                <p class="text-lg text-gray-700">Регистрация нового ребенка в системе</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="max-w-2xl mx-auto">
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <form action="{{ route('children.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Фамилия <span class="text-gray-500 text-xs">(до 50 символов)</span>
                                    </label>
                                    <input type="text" name="last_name" id="last_name" 
                                           class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                           value="{{ old('last_name') }}" required maxlength="50">
                                </div>
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Имя <span class="text-gray-500 text-xs">(до 50 символов)</span>
                                    </label>
                                    <input type="text" name="first_name" id="first_name" 
                                           class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                           value="{{ old('first_name') }}" required maxlength="50">
                                </div>
                                <div>
                                    <label for="patronymic" class="block text-sm font-medium text-gray-700 mb-2">
                                        Отчество <span class="text-gray-500 text-xs">(до 50 символов)</span>
                                    </label>
                                    <input type="text" name="patronymic" id="patronymic" 
                                           class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                           value="{{ old('patronymic') }}" maxlength="50">
                                </div>
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Дата рождения</label>
                                <input type="date" name="birth_date" id="birth_date" 
                                       class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                       value="{{ old('birth_date') }}" required>
                                <p class="mt-1 text-sm text-gray-500">Ребенку должно быть от 1.5 до 8 лет</p>
                            </div>

                            <div>
                                <label for="group_id" class="block text-sm font-medium text-gray-700 mb-2">Группа</label>
                                <select name="group_id" id="group_id" 
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" 
                                            {{ old('group_id') == $group->id ? 'selected' : '' }}
                                            data-child-count="{{ $group->children_count }}">
                                            {{ $group->name }} ({{ $group->children_count }}/15)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Родитель</label>
                                <select name="parent_id" id="parent_id" 
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('children.index') }}" 
                                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center transition">
                                    Отмена
                                </a>
                                <button type="submit" 
                                        class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition text-center">
                                    Добавить ребенка
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Установка ограничений по дате рождения
        const today = new Date();
        const minDate = new Date();
        minDate.setFullYear(today.getFullYear() - 8);
        
        const maxDate = new Date();
        maxDate.setFullYear(today.getFullYear() - 1);
        maxDate.setMonth(today.getMonth() - 6);

        document.getElementById('birth_date').setAttribute('min', minDate.toISOString().split('T')[0]);
        document.getElementById('birth_date').setAttribute('max', maxDate.toISOString().split('T')[0]);

        // Проверка количества детей в группе перед отправкой
        document.querySelector('form').addEventListener('submit', function(e) {
            const groupSelect = document.getElementById('group_id');
            const selectedOption = groupSelect.options[groupSelect.selectedIndex];
            const childCount = parseInt(selectedOption.getAttribute('data-child-count'));
            
            if (childCount >= 15) {
                e.preventDefault();
                alert('В выбранной группе уже максимальное количество детей (15).');
            }
        });
    </script>

    <x-footer/>
</body>
</html>