<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>Фотогаллерея</title>
</head>
<body class="bg-gray-100">
    <x-header/>
    
    
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
          <h1 class="text-3xl font-bold text-center mb-12">Фотогалерея</h1>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($photos as $photo)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
              <a href="{{ route('gallery.show', $photo) }}" class="block">
                <div class="h-48 overflow-hidden">
                  <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-1">{{ $photo->title }}</h3>
                  @if($photo->description)
                  <p class="text-gray-600 text-sm">{{ Str::limit($photo->description, 50) }}</p>
                  @endif
                </div>
              </a>
            </div>
            @endforeach
          </div>
      
          <div class="mt-8">
            {{ $photos->links() }}
          </div>
        </div>
      </section>
</body>
</html>