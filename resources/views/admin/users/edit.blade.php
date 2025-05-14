<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать пользователя - 7 Звезд</title>
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
    </style>
</head>
<body class="bg-gray-100">
    <x-header/>

    <main class="flex-grow">
        <!-- Герой секция -->
        <div class="bg-gradient-to-r from-purple-100 to-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-3xl font-bold gradient-text mb-2">Редактировать пользователя</h1>
                <p class="text-lg text-gray-700">Редактирование учетной записи</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg hover:shadow-xl transition">
                <div class="p-8">
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="last_name" class="block text-gray-700 font-medium mb-2">Фамилия</label>
                            <input type="text" name="last_name" id="last_name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                   required value="{{ $user->last_name }}" maxlength="50">
                        </div>

                        <div>
                            <label for="first_name" class="block text-gray-700 font-medium mb-2">Имя</label>
                            <input type="text" name="first_name" id="first_name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                   required value="{{ $user->first_name }}" maxlength="50">
                        </div>

                        <div>
                            <label for="patronymic" class="block text-gray-700 font-medium mb-2">Отчество</label>
                            <input type="text" name="patronymic" id="patronymic" value="{{ $user->patronymic }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition" maxlength="50">
                        </div>

                        <div>
                            <label for="status" class="block text-gray-700 font-medium mb-2">Статус</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                    required>
                                    <option value="parent" {{ $user->status == 'parent' ? 'selected' : '' }}>Родитель</option>
                                    <option value="educator" {{ $user->status == 'educator' ? 'selected' : '' }}>Воспитатель</option>
                            </select>
                        </div>

                        <div>
                            <label for="phone_number" class="block text-gray-700 font-medium mb-2">Телефон</label>
                            <input type="text" name="phone_number" id="phone_number" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                   placeholder="8 888 888 88 88" required value="{{ $user->phone_number }}">
                        </div>

                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" name="email" id="email" maxlength="100"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                   required value="{{ $user->email }}">
                        </div>

                        <div>
                            <label for="login" class="block text-gray-700 font-medium mb-2">Логин</label>
                            <input type="text" name="login" id="login" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                   maxlength="5" minlength="5" required value="{{ $user->login }}">
                        </div>

                        <div>
                            <label for="password" class="block text-gray-700 font-medium mb-2">Пароль</label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                   required>
                        </div>

                        <button type="submit" 
                                class="gradient-bg text-white px-6 py-3 rounded-lg hover:opacity-90 transition w-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4A3F9B]">
                            Сохранить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <x-footer/>

    <script>
        document.getElementById('phone_number').addEventListener('input', function (e) {
            let input = e.target.value.replace(/\D/g, '');
            let formatted = '';
    
            if (input.length > 0) formatted = '8';
            if (input.length > 1) formatted += ' ' + input.substring(1, 4);
            if (input.length > 4) formatted += ' ' + input.substring(4, 7);
            if (input.length > 7) formatted += ' ' + input.substring(7, 9);
            if (input.length > 9) formatted += ' ' + input.substring(9, 11);
    
            e.target.value = formatted;
        });
    </script>
</body>
</html>