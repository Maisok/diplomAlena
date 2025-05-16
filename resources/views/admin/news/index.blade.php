<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новости - 7 Звезд</title>
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
        .description-cell {
            max-width: 300px;
            word-wrap: break-word;
        }
        .news-card {
            transition: all 0.2s ease;
        }
        .news-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        
    </style>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
</head>
<body class="bg-gray-100">
    <x-header/>

    <main class="flex-grow">
        <!-- Герой секция -->
        <div class="bg-gradient-to-r from-purple-100 to-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-3xl font-bold gradient-text mb-2">Управление новостями</h1>
                <p class="text-lg text-gray-700">Список всех новостей организации</p>
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

                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('news.create') }}" 
                       class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
                        Добавить новость
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Заголовок</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Описание</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Изображение</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Автор</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($news as $item)
                                <tr class="table-row transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 break-words max-w-[200px] line-clamp-2">
                                            {{ Str::limit($item->title, 200) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500 break-words max-w-[400px] line-clamp-3">
                                            {{ Str::limit($item->description, 1000) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" 
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->admin->last_name }} {{ $item->admin->first_name }} {{ $item->admin->patronymic }}
                                        <span class="text-gray-400">#{{ $item->admin->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('news.edit', $item) }}" 
                                           class="text-[#4A3F9B] hover:text-[#D32F2F] mr-4 transition">Редактировать</a>
                                        <form action="{{ route('news.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Вы уверены?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($news->lastPage() > 1)
                    <div class="m-8 flex justify-center">
                      <div class="flex space-x-2">
                        @if($news->currentPage() > 1)
                          <a href="{{ $news->previousPageUrl() }}" class="px-4 py-2 bg-white rounded-lg shadow hover:bg-purple-50">
                            Назад
                          </a>
                        @endif
                        
                        @for($i = 1; $i <= $news->lastPage(); $i++)
                          <a href="{{ $news->url($i) }}" class="px-4 py-2 {{ $i == $news->currentPage() ? 'bg-purple-600 text-white' : 'bg-white' }} rounded-lg shadow hover:bg-purple-50">
                            {{ $i }}
                          </a>
                        @endfor
                        
                        @if($news->hasMorePages())
                          <a href="{{ $news->nextPageUrl() }}" class="px-4 py-2 bg-white rounded-lg shadow hover:bg-purple-50">
                            Вперед
                          </a>
                        @endif
                      </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <x-footer/>
</body>
</html>