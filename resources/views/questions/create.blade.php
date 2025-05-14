<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задать вопрос - 7 Звезд</title>
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
                <h1 class="text-3xl font-bold gradient-text mb-2">Задать вопрос</h1>
                <p class="text-lg text-gray-700">Мы всегда рады ответить на ваши вопросы</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                <div class="p-8">
                    @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                        <p>{{ session('success') }}</p>
                    </div>
                    @endif

                    <form action="{{ route('questions.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-8">
                            <label for="question" class="block text-gray-700 font-medium mb-3">Ваш вопрос</label>
                            <textarea name="question" id="question" rows="6" maxlength="1000"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition"
                                required></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <button type="submit" 
                                class="gradient-bg text-white px-8 py-3 rounded-lg hover:opacity-90 transition flex-1 sm:flex-none focus:outline-none focus:ring-2 focus:ring-[#4A3F9B] focus:ring-offset-2">
                                Отправить вопрос
                            </button>
                            
                            <a href="{{ route('questions.all') }}" 
                                class="text-[#4A3F9B] hover:text-[#D32F2F] font-medium transition flex items-center">
                                Посмотреть все вопросы
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <x-footer/>
</body>
</html>