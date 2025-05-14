<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вопросы - 7 Звезд</title>
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
        .table-row:hover {
            background-color: #f9fafb;
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
                <h1 class="text-3xl font-bold gradient-text mb-2">Управление вопросами</h1>
                <p class="text-lg text-gray-700">Список всех вопросов от пользователей</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="max-w-6xl mx-auto">
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

                <div class="grid grid-cols-1 gap-8">
                    <!-- Новые вопросы -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden question-card">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold gradient-text">Новые вопросы</h2>
                        </div>
                        
                        <div class="p-6">
                            @if($unanswered->isEmpty())
                                <p class="text-gray-500 text-center py-4">Нет новых вопросов</p>
                            @else
                                <div class="space-y-6">
                                    @foreach($unanswered as $question)
                                    <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                                        <div class="font-medium text-gray-800 mb-3">Вопрос: {{ $question->question }}</div>
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('admin.questions.show', $question) }}" 
                                               class="text-[#4A3F9B] hover:text-[#D32F2F] transition">Ответить</a>
                                            <form action="{{ route('admin.questions.destroy', $question) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition">Удалить</button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Отвеченные вопросы -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden question-card">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold gradient-text">Отвеченные вопросы</h2>
                        </div>
                        
                        <div class="p-6">
                            @if($answered->isEmpty())
                                <p class="text-gray-500 text-center py-4">Нет отвеченных вопросов</p>
                            @else
                                <div class="space-y-6">
                                    @foreach($answered as $question)
                                    <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                                        <div class="font-medium text-gray-800 mb-1">Вопрос: {{ $question->question }}</div>
                                        <div class="text-gray-600 mb-2">Ответ: {{ $question->answer }}</div>
                                        <div class="flex items-center flex-wrap gap-2 text-sm text-gray-500 mb-3">
                                            <span class="px-2 py-1 rounded-full 
                                                {{ $question->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $question->is_published ? 'Опубликован' : 'Не опубликован' }}
                                            </span>
                                            <span>•</span>
                                            <span>{{ $question->answered_at }}</span>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('admin.questions.show', $question) }}" 
                                               class="text-[#4A3F9B] hover:text-[#D32F2F] transition">Изменить</a>
                                            <form action="{{ route('admin.questions.destroy', $question) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition">Удалить</button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-6">
                                    {{ $answered->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer/>
</body>
</html>