<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Семь звёзд</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
  </style>
</head>
<body class="bg-gray-100 font-sans">

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
      <div class="bg-white rounded-xl shadow-md overflow-hidden hover-scale transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="relative h-48 bg-purple-200 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
          <div class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-semibold text-purple-700">15.10.2024</div>
        </div>
        <div class="p-6">
          <h3 class="font-semibold text-xl mb-3">Мы открылись!</h3>
          <p class="text-gray-600 mb-4">
            Наша работа направлена на гармоничное и всестороннее развитие детей, на сохранение и укрепление их физических...
          </p>
          <button class="text-purple-600 font-semibold hover:text-purple-800 transition flex items-center">
            Читать далее
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-md overflow-hidden hover-scale transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="relative h-48 bg-orange-200 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
          <div class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-semibold text-orange-700">10.10.2024</div>
        </div>
        <div class="p-6">
          <h3 class="font-semibold text-xl mb-3">Новые программы</h3>
          <p class="text-gray-600 mb-4">
            Представляем новые развивающие программы для детей разных возрастов с учетом современных образовательных стандартов...
          </p>
          <button class="text-purple-600 font-semibold hover:text-purple-800 transition flex items-center">
            Читать далее
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-md overflow-hidden hover-scale transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="relative h-48 bg-blue-200 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
          <div class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-sm font-semibold text-blue-700">05.10.2024</div>
        </div>
        <div class="p-6">
          <h3 class="font-semibold text-xl mb-3">День открытых дверей</h3>
          <p class="text-gray-600 mb-4">
            Приглашаем всех желающих на день открытых дверей, где вы сможете познакомиться с нашими педагогами и методиками...
          </p>
          <button class="text-purple-600 font-semibold hover:text-purple-800 transition flex items-center">
            Читать далее
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
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
        <button class="mt-4 md:mt-0 bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700 transition flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
          ЗАДАТЬ ВОПРОС
        </button>
      </div>
      
      <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 hover-scale">
        <div class="flex justify-between items-center mb-6">
          <span class="text-gray-500">Всего вопросов: 15</span>
          <button class="text-purple-600 font-semibold hover:text-purple-800 transition">
            Показать все
          </button>
        </div>
        
        <div class="space-y-6">
          <div class="border-b border-gray-100 pb-6">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-semibold text-lg text-purple-700 mb-2">Сколько в одной группе малышей?</p>
                <p class="text-gray-600">
                  В одной группе список максимален — 10 человек. Это оптимальное количество детей, чтобы преподаватель мог полноценно уделить время каждому.
                </p>
              </div>
              <button class="text-purple-600 ml-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </div>
          </div>
          
          <div class="border-b border-gray-100 pb-6">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-semibold text-lg text-purple-700 mb-2">Какие документы нужны для зачисления?</p>
                <p class="text-gray-600">
                  Для зачисления необходимы: копия свидетельства о рождении, медицинская карта ребенка, заявление от родителей и договор.
                </p>
              </div>
              <button class="text-purple-600 ml-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </div>
          </div>
          
          <div class="border-b border-gray-100 pb-6">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-semibold text-lg text-purple-700 mb-2">Есть ли у вас видеонаблюдение?</p>
                <p class="text-gray-600">
                  Да, во всех помещениях детского сада установлено видеонаблюдение. Родители могут получить доступ к записи по запросу.
                </p>
              </div>
              <button class="text-purple-600 ml-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </div>
          </div>
        </div>
        
        <button class="w-full mt-8 bg-purple-100 text-purple-700 font-semibold py-3 rounded-lg hover:bg-purple-200 transition flex items-center justify-center">
          Показать больше вопросов
        </button>
      </div>
    </div>
  </div>
</section>

<!-- Галерея -->
<section class="py-16 bg-white">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-12">ФОТОГАЛЕРЕЯ</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="relative group overflow-hidden rounded-xl h-64 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="absolute inset-0 bg-purple-300"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
          <span class="text-white font-semibold text-lg">Наши малыши</span>
        </div>
      </div>
      
      <div class="relative group overflow-hidden rounded-xl h-64 animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="absolute inset-0 bg-orange-300"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
          <span class="text-white font-semibold text-lg">Занятия</span>
        </div>
      </div>
      
      <div class="relative group overflow-hidden rounded-xl h-64 animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="absolute inset-0 bg-blue-300"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
          <span class="text-white font-semibold text-lg">Праздники</span>
        </div>
      </div>
      
      <div class="relative group overflow-hidden rounded-xl h-64 animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="absolute inset-0 bg-green-300"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
          <span class="text-white font-semibold text-lg">Прогулки</span>
        </div>
      </div>
      
      <div class="relative group overflow-hidden rounded-xl h-64 md:col-span-2 animate-fade-in-up" style="animation-delay: 0.5s;">
        <div class="absolute inset-0 bg-yellow-300"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
          <span class="text-white font-semibold text-lg">Наша территория</span>
        </div>
      </div>
      
      <div class="relative group overflow-hidden rounded-xl h-64 animate-fade-in-up" style="animation-delay: 0.6s;">
        <div class="absolute inset-0 bg-pink-300"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
          <span class="text-white font-semibold text-lg">Творчество</span>
        </div>
      </div>
      
      <div class="relative group overflow-hidden rounded-xl h-64 animate-fade-in-up" style="animation-delay: 0.7s;">
        <div class="absolute inset-0 bg-indigo-300"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
          <span class="text-white font-semibold text-lg">Спорт</span>
        </div>
      </div>
    </div>
    
    <div class="text-center mt-8">
      <button class="bg-purple-600 text-white px-6 py-3 rounded-full hover:bg-purple-700 transition inline-flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Смотреть все фото
      </button>
    </div>
  </div>
</section>

<x-footer/>

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
</body>
</html>





  {{-- <!-- Main Content -->
  <main class="flex-grow container mx-auto py-10">
    <div class="mb-6 flex justify-between items-center">
      <a href="#" class="text-gray-600 underline">Вернуться в личный кабинет</a>
      <a href="#" class="text-gray-600 underline">Обновить страницу</a>
    </div>

    <h2 class="text-center text-2xl font-bold mb-6">РАСПИСАНИЕ ГРУППЫ</h2>

    <!-- Schedule Grid -->
    <div class="grid grid-cols-6 gap-2">
      <!-- Time Column -->
      <div class="flex flex-col space-y-2 text-gray-500">
        <div class="h-16">09:00</div>
        <div class="h-16">10:00</div>
        <div class="h-16">11:00</div>
        <div class="h-16">12:00</div>
        <div class="h-16">13:00</div>
        <div class="h-16">14:00</div>
        <div class="h-16">15:00</div>
        <div class="h-16">16:00</div>
        <div class="h-16">17:00</div>
        <div class="h-16">18:00</div>
      </div>

      <!-- Days Columns -->
      <div class="col-span-5 grid grid-cols-5 gap-2">
        <!-- Monday -->
        <div class="flex flex-col space-y-2">
          <div class="bg-purple-100 rounded p-2 text-center">Завтрак<br><span class="text-xs">09:00 - 09:35</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Первое занятие<br><span class="text-xs">10:00 - 10:35</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Второе занятие<br><span class="text-xs">10:45 - 11:15</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Второй завтрак<br><span class="text-xs">11:25 - 12:00</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Прогулка<br><span class="text-xs">12:00 - 13:10</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Сон<br><span class="text-xs">13:30 - 15:30</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Подъем, полдник<br><span class="text-xs">15:30 - 16:00</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Третье занятие<br><span class="text-xs">16:00 - 16:35</span></div>
          <div class="bg-orange-100 rounded p-2 text-center">Игровая деятельность<br><span class="text-xs">16:40 - 19:00</span></div>
        </div>

        <!-- Tuesday -->
        <div class="flex flex-col space-y-2">
          <div class="bg-purple-100 rounded p-2 text-center">Завтрак<br><span class="text-xs">09:00 - 09:25</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Первое занятие<br><span class="text-xs">09:50 - 10:25</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Второе занятие<br><span class="text-xs">10:45 - 11:15</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Игровая деятельность<br><span class="text-xs">11:20 - 12:00</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Сон<br><span class="text-xs">12:30 - 14:00</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Подъем, полдник<br><span class="text-xs">14:30 - 16:00</span></div>
          <div class="bg-orange-200 rounded p-2 text-center">Утренник<br><span class="text-xs">16:00 - 17:30</span></div>
          <div class="bg-orange-100 rounded p-2 text-center">Игровая деятельность<br><span class="text-xs">17:30 - 19:00</span></div>
        </div>

        <!-- Wednesday -->
        <div class="flex flex-col space-y-2">
          <div class="bg-purple-100 rounded p-2 text-center">Завтрак<br><span class="text-xs">09:00 - 09:35</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Первое занятие<br><span class="text-xs">10:00 - 10:35</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Второе занятие<br><span class="text-xs">10:45 - 11:15</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Второй завтрак<br><span class="text-xs">11:25 - 12:00</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Прогулка<br><span class="text-xs">12:00 - 13:10</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Сон<br><span class="text-xs">13:30 - 15:30</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Подъем, полдник<br><span class="text-xs">15:30 - 16:00</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Третье занятие<br><span class="text-xs">16:00 - 16:35</span></div>
          <div class="bg-orange-100 rounded p-2 text-center">Игровая деятельность<br><span class="text-xs">16:40 - 19:00</span></div>
        </div>

        <!-- Thursday -->
        <div class="flex flex-col space-y-2">
          <div class="bg-purple-100 rounded p-2 text-center">Завтрак<br><span class="text-xs">09:00 - 09:35</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Первое занятие<br><span class="text-xs">10:00 - 10:35</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Второе занятие<br><span class="text-xs">10:45 - 11:15</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Второй завтрак<br><span class="text-xs">11:25 - 12:00</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Прогулка<br><span class="text-xs">12:20 - 13:10</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Сон<br><span class="text-xs">13:30 - 15:30</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Подъем, полдник<br><span class="text-xs">15:30 - 16:00</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Третье занятие<br><span class="text-xs">16:00 - 16:35</span></div>
          <div class="bg-orange-100 rounded p-2 text-center">Игровая деятельность<br><span class="text-xs">16:40 - 19:00</span></div>
        </div>

        <!-- Friday -->
        <div class="flex flex-col space-y-2">
          <div class="bg-purple-100 rounded p-2 text-center">Завтрак<br><span class="text-xs">09:00 - 09:35</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Первое занятие<br><span class="text-xs">10:00 - 10:35</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Второе занятие<br><span class="text-xs">10:45 - 11:15</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Второй завтрак<br><span class="text-xs">11:25 - 12:00</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Прогулка<br><span class="text-xs">12:20 - 13:10</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Сон<br><span class="text-xs">13:30 - 15:30</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Подъем, полдник<br><span class="text-xs">15:30 - 16:00</span></div>
          <div class="bg-purple-100 rounded p-2 text-center">Третье занятие<br><span class="text-xs">16:00 - 16:35</span></div>
          <div class="bg-orange-100 rounded p-2 text-center">Игровая деятельность<br><span class="text-xs">16:40 - 19:00</span></div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-purple-500 text-white p-6 text-center text-sm">
    <div class="flex flex-col items-center space-y-2">
      <img src="logo.png" alt="Logo" class="h-10">
      <p>По вопросам обращаться по телефону: <br> <span class="font-bold">+7 (999) 123-45-67</span></p>
      <p>Анна Иванова</p>
    </div>
  </footer>
</div>
--}}

