<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Новости | Семь звёзд</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&display=swap" rel="stylesheet">
  <style>
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .animate-fade-in-up {
      animation: fadeInUp 0.8s ease-out forwards;
    }
    .hover-scale {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-scale:hover {
      transform: scale(1.03);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .gradient-text {
      background: linear-gradient(45deg, #7c3aed, #f59e0b);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }
    .font-rubik {
      font-family: 'Rubik Mono One', sans-serif;
    }
    .news-card {
      background-image: url('{{asset("images/Rectangle 42.png")}}');
      background-size: cover;
      background-position: center;
      background-blend-mode: overlay;
      background-color: rgba(255, 255, 255, 0.9);
    }
  </style>
</head>
<body class=" flex flex-col min-h-screen">

<x-header/>

<!-- Герой секция для новостей -->
<section class="relative py-16">
  <div class="container mx-auto px-4">
    <div class="bg-white bg-opacity-90 p-8 rounded-2xl shadow-lg max-w-3xl mx-auto text-center animate-fade-in-up">
      <h1 class="text-3xl md:text-4xl font-bold gradient-text mb-4">НОВОСТИ ДЕТСКОГО САДА</h1>
      <p class="text-gray-700 text-lg">Будьте в курсе последних событий и мероприятий</p>
    </div>
  </div>
</section>

<!-- Основной контент -->
<main class="flex-grow py-12">
  <div class="container mx-auto px-4">
    @if($newsItems->count() > 0)
      <div class="grid grid-cols-1 gap-8 max-w-5xl mx-auto">
        @foreach($newsItems as $news)
        <div class="news-card rounded-xl shadow-lg overflow-hidden hover-scale animate-fade-in-up" style="animation-delay: 0.{{ $loop->index }}s;">
          <div class="flex flex-col md:flex-row">
            <div class="md:w-1/3 h-48 md:h-auto overflow-hidden">
              <img class="w-full h-full object-cover" src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}">
            </div>
            <div class="p-6 md:w-2/3 flex flex-col">
              <div class="flex justify-between items-start mb-2">
                <h2 class="text-xl font-bold text-gray-800">{{ $news->title }}</h2>
                <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">
                  {{ $news->created_at->format('d.m.Y') }}
                </span>
              </div>
              <p class="text-gray-600 mb-4 flex-grow">{{ $news->description }}</p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      
      <!-- Простая пагинация -->
      @if($newsItems->lastPage() > 1)
      <div class="mt-8 flex justify-center">
        <div class="flex space-x-2">
          @if($newsItems->currentPage() > 1)
            <a href="{{ $newsItems->previousPageUrl() }}" class="px-4 py-2 bg-white rounded-lg shadow hover:bg-purple-50">
              Назад
            </a>
          @endif
          
          @for($i = 1; $i <= $newsItems->lastPage(); $i++)
            <a href="{{ $newsItems->url($i) }}" class="px-4 py-2 {{ $i == $newsItems->currentPage() ? 'bg-purple-600 text-white' : 'bg-white' }} rounded-lg shadow hover:bg-purple-50">
              {{ $i }}
            </a>
          @endfor
          
          @if($newsItems->hasMorePages())
            <a href="{{ $newsItems->nextPageUrl() }}" class="px-4 py-2 bg-white rounded-lg shadow hover:bg-purple-50">
              Вперед
            </a>
          @endif
        </div>
      </div>
      @endif
      
    @else
      <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl mx-auto text-center animate-fade-in-up">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Новостей пока нет</h3>
        <p class="text-gray-500">Следите за обновлениями, скоро мы добавим интересные новости!</p>
      </div>
    @endif
  </div>
</main>

<!-- Дополнительная информация -->
<section class="py-12 bg-gradient-to-b from-purple-100 to-white">
    <div class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg hover-scale text-center">
        <h2 class="text-2xl font-bold mb-6 gradient-text">Хотите быть в курсе всех событий?</h2>
        <p class="text-gray-700 mb-6">
          Подпишитесь на наш Telegram-канал, чтобы первыми узнавать о новых мероприятиях, праздниках и важных объявлениях.
        </p>
        
        <!-- Кнопка для перехода в Telegram -->
        <a href="https://t.me/kindgardennews" target="_blank" rel="noopener noreferrer" 
           class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-all duration-300">
          <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.14-.26.26-.534.26l.213-3.053 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
          </svg>
          Подписаться на Telegram
        </a>
        
        <p class="text-gray-500 text-sm mt-4">
          Нажмите на кнопку выше, чтобы перейти в наш канал
        </p>
      </div>
    </div>
  </section>

<x-footer/>

<script>
  // Анимация при скролле
  document.addEventListener('DOMContentLoaded', () => {
    const animateElements = document.querySelectorAll('.animate-fade-in-up');
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = 1;
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, { threshold: 0.1 });
    
    animateElements.forEach(el => {
      el.style.opacity = 0;
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
      observer.observe(el);
    });
  });
</script>
</body>
</html>