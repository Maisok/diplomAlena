<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Частые вопросы - 7 Звезд</title>
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
            transition: all 0.3s ease;
        }
        .question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-header/>

    <main class="flex-grow">
        <!-- Герой секция -->
        <div class="bg-gradient-to-r from-purple-100 to-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-3xl font-bold gradient-text mb-2">Частые вопросы</h1>
                <p class="text-lg text-gray-700">Ответы на часто задаваемые вопросы</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <a href="{{ route('questions.create') }}" 
                        class="gradient-bg text-white px-6 py-3 rounded-lg hover:opacity-90 transition inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Задать новый вопрос
                    </a>
                </div>

                <div class="space-y-6">
                    @foreach($questions as $question)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden question-card">
                        <div class="p-6">
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-[#4A3F9B] mb-2">Вопрос:</h3>
                                    <p class="text-gray-700 mb-4">{{ $question->question }}</p>
                                    
                                    <h3 class="text-xl font-semibold text-[#4A3F9B] mb-2">Ответ:</h3>
                                    <p class="text-gray-700">{{ $question->answer }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-3 text-sm text-gray-500 border-t border-gray-100">
                            Опубликовано {{ $question->answered_at }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </main>

    <x-footer/>
</body>
</html>