<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать категорию - 7 Звезд</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .gradient-text {
            background: linear-gradient(45deg, #4A3F9B, #D32F2F);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .gradient-bg {
            background: linear-gradient(45deg, #4A3F9B, #D32F2F);
        }
        .form-input {
            transition: all 0.2s ease;
        }
        .form-input:focus {
            border-color: #4A3F9B;
            box-shadow: 0 0 0 2px rgba(74, 63, 155, 0.2);
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-header/>

    <main class="flex-grow">
        <!-- Герой секция -->
        <div class="bg-gradient-to-r from-purple-100 to-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-3xl font-bold gradient-text mb-2">Редактировать категорию</h1>
                <p class="text-lg text-gray-700">Изменение параметров категории</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="max-w-2xl mx-auto">
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <form action="{{ route('activity-categories.update', $activityCategory) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Название</label>
                                <input type="text" name="name" id="name" maxlength="100"
                                       class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                       value="{{ old('name', $activityCategory->name) }}" 
                                       @if($isUsed) disabled @endif required>
                            </div>
                            
                            <div>
                                <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Длительность (минуты)</label>
                                <input type="number" name="duration_minutes" id="duration_minutes" 
                                       class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                       value="{{ old('duration_minutes', $activityCategory->duration_minutes) }}" 
                                       min="10" max="180" @if($isUsed) disabled @endif required>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="once_per_day" id="once_per_day" 
                                       class="form-checkbox h-5 w-5 text-[#4A3F9B] rounded focus:ring-[#4A3F9B] transition"
                                       value="1"
                                       {{ old('once_per_day', $activityCategory->once_per_day) ? 'checked' : '' }}
                                       @if($isUsed) disabled @endif>
                                <label for="once_per_day" class="ml-2 block text-sm font-medium text-gray-700">
                                    Только один раз в день
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="unique_across_groups" id="unique_across_groups" 
                                       class="form-checkbox h-5 w-5 text-[#4A3F9B] rounded focus:ring-[#4A3F9B] transition"
                                       value="1"
                                       {{ old('unique_across_groups', $activityCategory->unique_across_groups) ? 'checked' : '' }}
                                       @if($isUsed) disabled @endif>
                                <label for="unique_across_groups" class="ml-2 block text-sm font-medium text-gray-700">
                                    Уникально для всех групп (нельзя ставить в одно время для разных групп)
                                </label>
                            </div>

                            @if($isUsed)
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
                                <p>Эта категория используется в расписании и не может быть изменена.</p>
                            </div>
                            @endif

                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('activity-categories.index') }}" 
                                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center transition">Отмена</a>
                                <button type="submit" 
                                        class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition text-center"
                                        @if($isUsed) disabled @endif>
                                    Сохранить изменения
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer/>
</body>
</html>