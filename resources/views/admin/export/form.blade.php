<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Экспорт детей - 7 Звезд</title>
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
                <h1 class="text-3xl font-bold gradient-text mb-2">Экспорт данных детей</h1>
                <p class="text-lg text-gray-700">Выгрузка списка детей в формате Excel</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <form action="{{ route('export.children') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label for="group_id" class="block text-sm font-medium text-gray-700 mb-2">Группа</label>
                                <select name="group_id" id="group_id" 
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                        required>
                                    @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('children.index') }}" 
                                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center transition">Отмена</a>
                                <button type="submit" 
                                        class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition text-center">
                                    Скачать Excel
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