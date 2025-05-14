<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Семь звёзд</title>

  <title>7 звезд</title>
  <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
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

    #photoModal {
        transition: opacity 0.3s ease;
    }

    body.modal-open {
        overflow: hidden;
    }

    /* Стили для кнопок навигации */
    #prevPhoto, #nextPhoto {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 640px) {
        #prevPhoto, #nextPhoto {
            width: 40px;
            height: 40px;
        }
        #photoCaption {
            font-size: 16px;
            bottom: 2rem;
        }
    }
  </style>
</head>
<body class="bg-gray-100 font-sans flex flex-col ">

<x-header/>

<!-- Герой секция -->
<section class="relative py-20" style="background-image: url('{{asset('images/image 2.png')}}')">
  <div class="container mx-auto px-4">
    <div class="bg-white bg-opacity-90 p-8 rounded-2xl shadow-lg max-w-3xl mx-auto text-center animate-fade-in-up">
      <h1 class="text-3xl md:text-4xl font-bold gradient-text mb-6">Добро пожаловать в 7 звезд!</h1>
      
      <div class="bg-purple-100 p-6 rounded-xl mb-6 hover-scale transition-all duration-300 cursor-pointer">
        <p class="text-gray-800 text-lg">
          Частное дошкольное образовательное учреждение «Центр развития – детский сад "Семь звезд" имеет лицензию на осуществление образовательной деятельности (№ 9378 от 29.07.2016 г.) и более 13 лет предоставляет услуги в сфере дошкольного и дополнительного образования.
        </p>
      </div>

      <div class="bg-purple-100 p-6 rounded-xl hover-scale transition-all duration-300 cursor-pointer" onclick="showMessage()">
        <p class="text-gray-800 text-lg font-medium">Адрес: ул. Солнечная, д. 10, г. Иркутск</p>
        <p class="text-gray-800 text-lg font-medium mt-2">График работы: пн.-пт. с 8:00 до 19:00</p>
      </div>
    </div>
  </div>
</section>

<!-- О нас -->
<section class="py-16 bg-white">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 animate-fade-in-up">
      "В сердце <span class="gradient-text">каждого</span> малыша – целая планета возможностей!"
    </h2>
  
    <div class="flex flex-col md:flex-row gap-8 justify-center">
      <div class="border border-purple-200 p-6 rounded-xl text-center shadow-md hover-scale transition w-full md:w-80 cursor-pointer animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-2">От 1 года 6 месяцев</h3>
        <p class="text-gray-500">Специальные группы для малышей</p>
      </div>
  
      <div class="border border-purple-200 p-6 rounded-xl text-center shadow-md hover-scale transition w-full md:w-80 cursor-pointer animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-2">Растем вместе</h3>
        <p class="text-gray-500">От пеленок до школы</p>
      </div>
  
      <div class="border border-purple-200 p-6 rounded-xl text-center shadow-md hover-scale transition w-full md:w-80 cursor-pointer animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-2">Всегда на связи</h3>
        <p class="text-gray-500">И рады ответить вам на вопросы</p>
      </div>
    </div>
  </div>
</section>

<!-- Почему мы -->
<section class="py-16 bg-gradient-to-b from-purple-100 to-white">
  <div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg hover-scale">
      <h2 class="text-3xl font-extrabold mb-6 text-center md:text-left gradient-text">ПОЧЕМУ МЫ?</h2>
      <p class="mb-4 text-lg text-gray-700">
        Вы задумываетесь, почему именно наш детский сад?
      </p>
      <p class="text-lg text-gray-700">
        Мы уделяем особое внимание гармоничному развитию детей и с радостью заявляем, что полностью подготовим вашего малыша к школе. Наши опытные педагоги помогут освоить необходимые знания и навыки, чтобы переход в школьную жизнь был для вашего ребенка легким и радостным.
      </p>
      
      <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-purple-50 p-4 rounded-lg">
          <div class="text-purple-600 font-bold text-2xl mb-2">13+</div>
          <div class="text-gray-700">Лет успешной работы</div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
          <div class="text-purple-600 font-bold text-2xl mb-2">50+</div>
          <div class="text-gray-700">Выпускников ежегодно</div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
          <div class="text-purple-600 font-bold text-2xl mb-2">10</div>
          <div class="text-gray-700">Детей в группе максимум</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Новости -->
<section class="py-16 bg-white">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-12">НОВОСТИ</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($latestNews as $news)
      <div class="bg-white rounded-xl shadow-md overflow-hidden hover-scale transition-all duration-300 animate-fade-in-up">
        <div class="relative h-48 bg-purple-200 overflow-hidden">
          @if($news->image)
          <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
          @else
          <div class="w-full h-full bg-gradient-to-r from-purple-400 to-purple-600"></div>
          @endif
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
          <div class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-semibold text-purple-700">
            {{ $news->created_at->format('d.m.Y') }}
          </div>
        </div>
        <div class="p-6">
          <h3 class="font-semibold text-xl mb-3">{{ $news->title }}</h3>
          <p class="text-gray-600 mb-4">
            {{ Str::limit($news->description, 100) }}
          </p>
          <a href="{{ route('shownews.index') }}" class="text-purple-600 font-semibold hover:text-purple-800 transition flex items-center">
            Читать далее
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>
      </div>
      @endforeach
    </div>
    
    <div class="text-center mt-8">
      <a href="{{ route('shownews.index') }}" class="bg-purple-600 text-white px-6 py-3 rounded-full hover:bg-purple-700 transition inline-flex items-center">
        Все новости
      </a>
    </div>

    <div class="max-w-4xl mx-auto mt-12 text-center text-gray-700">
      <p class="mb-4">
        Частное дошкольное образовательное учреждение «Центр развития – детский сад "Семь звезд" имеет лицензию на осуществление образовательной деятельности (№ 9378 от 29.07.2016 г.) и более 13 лет предоставляет услуги в сфере дошкольного и дополнительного образования.
      </p>
      <p>
        В настоящее время мы нацелены на решение широкого круга задач в сфере образования и социальной сфере и на обеспечение возможности получения дошкольного образования детям в возрасте от 1 года 6 месяцев до 7 лет.
      </p>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto">
      <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h2 class="text-3xl font-bold">ЧАСТЫЕ ВОПРОСЫ</h2>
        <a href="{{ route('questions.create') }}" class="mt-4 md:mt-0 bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700 transition flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
          ЗАДАТЬ ВОПРОС
        </a>
      </div>
      
      <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 hover-scale">
        <div class="flex justify-between items-center mb-6">
          <span class="text-gray-500">Всего вопросов: {{ $questions->count() }}</span>
          <a href="{{ route('questions.all') }}" class="text-purple-600 font-semibold hover:text-purple-800 transition">
            Показать все
          </a>
        </div>
        
        <div class="space-y-6">
          @foreach($questions->take(3) as $question)
          <div class="border-b border-gray-100 pb-6">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-semibold text-lg text-purple-700 mb-2">ВОПРОС: {{ $question->question }}</p>
                <p class="text-gray-600">
                  {{ $question->answer }}
                </p>
              </div>
              <button class="text-purple-600 ml-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </div>
          </div>
          @endforeach
        </div>
        
        <a href="{{ route('questions.all') }}" class="w-full mt-8 bg-purple-100 text-purple-700 font-semibold py-3 rounded-lg hover:bg-purple-200 transition flex items-center justify-center">
          Показать больше вопросов
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Галерея -->
<section class="py-16 bg-white">
  <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-12">ФОТОГАЛЕРЕЯ</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="galleryContainer">
          @foreach($photos as $index => $photo)
          <div class="relative group overflow-hidden rounded-xl h-64 animate-fade-in-up" style="animation-delay: 0.{{ $loop->index }}s;">
              <div class="gallery-item cursor-pointer h-full" 
                   data-src="{{ asset('storage/' . $photo->image_path) }}" 
                   data-title="{{ $photo->title }}"
                   data-index="{{ $index }}">
                  <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->title }}" class="w-full h-full object-cover">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                      <span class="text-white font-semibold text-lg">{{ $photo->title }}</span>
                  </div>
              </div>
          </div>
          @endforeach
      </div>
      
      <div class="text-center mt-8">
          <a href="{{ route('gallery.index') }}" class="bg-purple-600 text-white px-6 py-3 rounded-full hover:bg-purple-700 transition inline-flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Смотреть все фото
          </a>
      </div>
  </div>
</section>

<x-footer/>
<div id="photoModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center p-4">
  <div class="relative w-full max-w-4xl max-h-[90vh] flex items-center justify-center">
      <button id="closeModal" class="absolute top-0 right-0 -mt-10 -mr-2 text-white text-4xl z-50 hover:text-gray-300 transition">
          &times;
      </button>
      
      <button id="prevPhoto" class="absolute left-0 -ml-12 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition z-50">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
      </button>
      
      <div class="w-full h-full flex items-center justify-center">
          <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] object-contain">
      </div>
      
      <button id="nextPhoto" class="absolute right-0 -mr-12 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition z-50">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
      </button>
      
      <div id="photoCaption" class="absolute bottom-0 left-0 right-0 text-center text-white text-lg font-semibold pb-4"></div>
  </div>
</div>
<script>
  // Простая функция для демонстрации
  function showMessage() {
    alert("Мы ждем вас по адресу: ул. Солнечная, д. 10, г. Иркутск");
  }
  
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
<script>
  document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('photoModal');
      const modalImage = document.getElementById('modalImage');
      const photoCaption = document.getElementById('photoCaption');
      const closeModal = document.getElementById('closeModal');
      const prevPhoto = document.getElementById('prevPhoto');
      const nextPhoto = document.getElementById('nextPhoto');
      
      // Получаем все элементы галереи
      const galleryItems = document.querySelectorAll('.gallery-item');
      let currentIndex = 0;
      let photos = [];
      
      // Собираем данные о фотографиях
      galleryItems.forEach(item => {
          photos.push({
              src: item.getAttribute('data-src'),
              title: item.getAttribute('data-title')
          });
      });
      
      // Открытие модального окна
      galleryItems.forEach(item => {
          item.addEventListener('click', function() {
              currentIndex = parseInt(this.getAttribute('data-index'));
              updateModal();
              modal.classList.remove('hidden');
              document.body.style.overflow = 'hidden'; // Блокируем скролл страницы
          });
      });
      
      // Закрытие модального окна
      closeModal.addEventListener('click', function() {
          modal.classList.add('hidden');
          document.body.style.overflow = 'auto'; // Восстанавливаем скролл страницы
      });
      
      // Клик по затемненной области
      modal.addEventListener('click', function(e) {
          if (e.target === modal) {
              modal.classList.add('hidden');
              document.body.style.overflow = 'auto';
          }
      });
      
      // Навигация по фотографиям
      prevPhoto.addEventListener('click', function(e) {
          e.stopPropagation();
          currentIndex = (currentIndex - 1 + photos.length) % photos.length;
          updateModal();
      });
      
      nextPhoto.addEventListener('click', function(e) {
          e.stopPropagation();
          currentIndex = (currentIndex + 1) % photos.length;
          updateModal();
      });
      
      // Навигация с клавиатуры
      document.addEventListener('keydown', function(e) {
          if (!modal.classList.contains('hidden')) {
              if (e.key === 'Escape') {
                  modal.classList.add('hidden');
                  document.body.style.overflow = 'auto';
              } else if (e.key === 'ArrowLeft') {
                  currentIndex = (currentIndex - 1 + photos.length) % photos.length;
                  updateModal();
              } else if (e.key === 'ArrowRight') {
                  currentIndex = (currentIndex + 1) % photos.length;
                  updateModal();
              }
          }
      });
      
      // Обновление содержимого модального окна
      function updateModal() {
          modalImage.src = photos[currentIndex].src;
          modalImage.alt = photos[currentIndex].title;
          photoCaption.textContent = photos[currentIndex].title;
      }
  });
  </script>
</body>
</html>