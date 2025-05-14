<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Галерея - 7 Звезд</title>
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
        .photo-card {
            transition: all 0.3s ease;
        }
        .photo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .form-input {
            transition: all 0.2s ease;
        }
        .form-input:focus {
            border-color: #4A3F9B;
            box-shadow: 0 0 0 2px rgba(74, 63, 155, 0.2);
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-header/>

    <main class="flex-grow">
        <!-- Герой секция -->
        <div class="bg-gradient-to-r from-purple-100 to-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-3xl font-bold gradient-text mb-2">Управление галереей</h1>
                <p class="text-lg text-gray-700">Фотогалерея организации</p>
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
                    <a href="{{ route('admin.gallery.create') }}" 
                       class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
                        Добавить фото
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    @if($photos->isEmpty())
                        <div class="p-8 text-center text-gray-500">
                            В галерее пока нет фотографий
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                            @foreach($photos as $photo)
                            <div class="photo-card border border-gray-200 rounded-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $photo->image_path) }}" 
                                     alt="{{ $photo->title }}" 
                                     class="w-full h-56 object-cover">
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800">{{ $photo->title }}</h3>
                                    @if($photo->description)
                                        <p class="text-gray-600 text-sm mt-2">{{ $photo->description }}</p>
                                    @endif
                                    <div class="flex justify-end space-x-3 mt-4">
                                        <a href="{{ route('admin.gallery.edit', $photo) }}" 
                                           class="text-[#4A3F9B] hover:text-[#D32F2F] transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.gallery.destroy', $photo) }}" method="POST" onsubmit="return confirm('Удалить это фото?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="px-6 py-4 border-t">
                            {{ $photos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <x-footer/>
</body>
</html>