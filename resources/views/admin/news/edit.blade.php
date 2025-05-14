<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать новость - 7 Звезд</title>
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
                <h1 class="text-3xl font-bold gradient-text mb-2">Редактировать новость</h1>
                <p class="text-lg text-gray-700">Обновление новости</p>
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

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <form action="{{ route('news.update', $news) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Заголовок <span class="text-gray-500 text-xs">(до 200 символов)</span>
                                </label>
                                <input type="text" name="title" id="title" 
                                       class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                       value="{{ old('title', $news->title) }}" required maxlength="200">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                                <textarea name="description" id="description" rows="8"
                                          class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg"
                                          required>{{ old('description', $news->description) }}</textarea>
                            </div>

                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Изображение</label>
                                <input type="file" name="image" id="image" 
                                       class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <p class="mt-1 text-sm text-gray-500">Допустимые форматы: JPEG, PNG, JPG, GIF, SVG. Максимальный размер: 2MB</p>
                                
                                @if($news->image)
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-700 mb-2">Текущее изображение:</p>
                                        <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" 
                                             class="max-w-xs h-auto rounded-lg border border-gray-200">
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="remove_image" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-600">Удалить изображение</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('news.index') }}" 
                                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center transition">
                                    Отмена
                                </a>
                                <button type="submit" 
                                        class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition text-center">
                                    Обновить новость
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