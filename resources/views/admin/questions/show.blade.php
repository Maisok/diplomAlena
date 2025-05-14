<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ответ на вопрос - 7 Звезд</title>
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
        .question-card {
            transition: all 0.2s ease;
        }
        .question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-header/>

    <main class="flex-grow">
        <!-- Герой секция -->
        <div class="bg-gradient-to-r from-purple-100 to-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-3xl font-bold gradient-text mb-2">Ответ на вопрос</h1>
                <p class="text-lg text-gray-700">Форма ответа на вопрос пользователя</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-lg overflow-hidden question-card">
                    <div class="p-6">
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold gradient-text mb-3">Вопрос:</h2>
                            <p class="bg-gray-50 p-4 rounded-lg text-gray-800">{{ $question->question }}</p>
                        </div>

                        @if($userData)
                        <div class="mb-8 bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <h3 class="font-semibold gradient-text mb-3">Данные пользователя:</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Имя:</p>
                                    <p class="font-medium">{{ $userData['name'] }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email:</p>
                                    <p class="font-medium">{{ $userData['email'] }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Телефон:</p>
                                    <p class="font-medium">{{ $userData['phone'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <form action="{{ route('admin.questions.answer', $question) }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">Ответ:</label>
                                <textarea name="answer" id="answer" rows="6" maxlength="1000"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                    placeholder="Введите ваш ответ...">{{ old('answer', $question->answer) }}</textarea>
                            </div>

                            <div class="mb-8">
                                <label class="inline-flex items-center">
                                    <input type="hidden" name="publish" value="0">
                                    <input type="checkbox" name="publish" value="1" 
                                           class="rounded border-gray-300 text-[#4A3F9B] shadow-sm focus:border-[#4A3F9B] focus:ring focus:ring-offset-0 focus:ring-[#4A3F9B] focus:ring-opacity-50"
                                           {{ $question->is_published ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">Опубликовать вопрос с ответом</span>
                                </label>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('admin.questions.index') }}" 
                                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center transition">Отмена</a>
                                <button type="submit" 
                                        class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition text-center">
                                    Сохранить ответ
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