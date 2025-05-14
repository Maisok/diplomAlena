<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить группу - 7 Звезд</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; }
        .gradient-text { background: linear-gradient(45deg, #4A3F9B, #D32F2F); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .gradient-bg { background: linear-gradient(45deg, #4A3F9B, #D32F2F); }
        .form-input { transition: all 0.2s ease; }
        .form-input:focus { border-color: #4A3F9B; box-shadow: 0 0 0 2px rgba(74, 63, 155, 0.2); }
        .disabled-option { color: #9CA3AF; background-color: #F3F4F6; }
    </style>
</head>
<body class="bg-gray-100">
    <x-header/>

    <main class="flex-grow">
        <div class="bg-gradient-to-r from-purple-100 to-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-3xl font-bold gradient-text mb-2">Добавить группу</h1>
                <p class="text-lg text-gray-700">Создание новой группы</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <form action="{{ route('groups.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Название группы <span class="text-gray-500 text-sm">(до 100 символов)</span>
                                </label>
                                <input type="text" name="name" id="name" 
                                       class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                       value="{{ old('name') }}" required maxlength="100">
                            </div>

                            <div>
                                <label for="educator_id" class="block text-sm font-medium text-gray-700 mb-2">Воспитатель</label>
                                <select name="educator_id" id="educator_id" 
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                    @foreach($educators as $educator)
                                        <option value="{{ $educator->id }}" {{ old('educator_id') == $educator->id ? 'selected' : '' }}>
                                            {{ $educator->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Доступны только воспитатели без групп</p>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('groups.index') }}" 
                                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center transition">
                                    Отмена
                                </a>
                                <button type="submit" 
                                        class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition text-center">
                                    Создать группу
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