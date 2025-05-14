<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Фотогаллерея</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-100">
    <x-header/>
    
    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
          <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="relative h-96">
              <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->title }}" class="w-full h-full object-contain">
            </div>
            <div class="p-6">
              <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $photo->title }}</h1>
              <p class="text-gray-600">{{ $photo->description }}</p>
            </div>
          </div>
      
          <div class="flex justify-between mt-8">
            <a href="{{ route('gallery.show', $prevPhoto) }}" class="bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700 transition flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Предыдущее
            </a>
            <a href="{{ route('gallery.index') }}" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-full hover:bg-gray-300 transition">
              В галерею
            </a>
            <a href="{{ route('gallery.show', $nextPhoto) }}" class="bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700 transition flex items-center">
              Следующее
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>
      </div>
</body>
</html>