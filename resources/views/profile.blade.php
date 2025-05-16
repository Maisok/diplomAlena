<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Профиль | 7 звёзд</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
    .schedule-item {
      transition: all 0.3s ease;
    }
    .schedule-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
  </style>

<style>
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: white;
      min-width: 200px;
      box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
      z-index: 1;
      border-radius: 8px;
      max-height: 300px;
      overflow-y: auto;
    }
    .dropdown:hover .dropdown-content {
      display: block;
    }
    .group-tabs {
      display: flex;
      border-bottom: 2px solid #e2e8f0;
      margin-bottom: 1rem;
    }
    .group-tab {
      padding: 0.5rem 1rem;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      margin-right: 0.5rem;
    }
    .group-tab.active {
      border-color: #7c3aed;
      color: #7c3aed;
      font-weight: 600;
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">

<x-header/>

<section class="py-12">
  <div class="container mx-auto px-4">
    <!-- Основная карточка профиля -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-scale animate-fade-in-up max-w-6xl mx-auto">
      <div class="bg-gradient-to-r from-purple-500 to-purple-700 p-6 text-white">
        <div class="flex items-center space-x-4">
          <div>
            <h1 class="text-2xl font-bold">{{ $user->first_name }} {{ $user->last_name }}</h1>
            <p class="opacity-90">
              @if($user->isAdmin()) Администратор @endif
              @if($user->isEducator()) Воспитатель @endif
              @if($user->isParent()) Родитель @endif
            </p>
          </div>
        </div>
      </div>
      
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-purple-50 p-4 rounded-lg">
            <h3 class="font-semibold text-purple-700 mb-2">Контактная информация</h3>
            <p class="text-gray-700"><span class="font-medium">Email:</span> {{ $user->email }}</p>
            <p class="text-gray-700"><span class="font-medium">Телефон:</span> {{ $user->phone_number }}</p>
            <p class="text-gray-700"><span class="font-medium">Логин:</span> {{ $user->login }}</p>
          </div>
          
          @if($user->isEducator() && $group)
          <div class="bg-purple-50 p-4 rounded-lg">
            <h3 class="font-semibold text-purple-700 mb-2">Группа</h3>
            <p class="text-gray-700"><span class="font-medium">Название:</span> {{ $group->name }}</p>
            <p class="text-gray-700"><span class="font-medium">Детей в группе:</span> {{ $children->count() }}</p>
          </div>
          @endif
          
          @if($user->isParent() && $children->count())
          <div class="bg-purple-50 p-4 rounded-lg">
            <h3 class="font-semibold text-purple-700 mb-2">Дети</h3>
            @foreach($children as $child)
            <p class="text-gray-700">{{ $child->first_name }} {{ $child->last_name }} ({{ $child->group->name }})</p>
            @endforeach
          </div>
          @endif
        </div>
        
        <div class="mt-6 text-center">
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-2 rounded-full hover:shadow-lg transition-all">
              Выйти из аккаунта
            </button>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Блок для воспитателя -->
    @if($user->isEducator() && $group)
    <div class="mt-12 bg-white rounded-xl shadow-lg overflow-hidden hover-scale animate-fade-in-up max-w-6xl mx-auto" style="animation-delay: 0.1s;">
        <div class="bg-gradient-to-r from-purple-500 to-purple-700 p-6 text-white flex justify-between items-center">
            <h2 class="text-xl font-bold">Группа {{ $group->name }}</h2>
            
            <!-- Выпадающий список детей -->
            <div class="dropdown relative">
              <button class="bg-white text-purple-700 px-4 py-2 rounded-full hover:bg-purple-50 transition flex items-center">
                <span>Дети в группе ({{ $children->count() }})</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div class="dropdown-content mt-2 p-4">
                <ul class="space-y-2">
                  @foreach($children as $child)
                  <li class="flex items-center space-x-3 p-2 hover:bg-purple-50 rounded">
                    <span class="text-purple-700">{{ $child->last_name }} {{ $child->first_name }}</span>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
      <div class="bg-gradient-to-r from-purple-500 to-purple-700 p-6 text-white">
        <h2 class="text-xl font-bold">Расписание группы {{ $group->name }}</h2>
        
      </div>
      
     
     <div class="p-6">
      @include('partials.group_schedule', ['schedule_items' => $schedule_items])
     </div>
     
    </div>
    </div>
    @endif
    
    <!-- Блок для родителя -->
    @if($user->isParent() && $children->count())
    <div class="mt-12 bg-white rounded-xl shadow-lg overflow-hidden hover-scale animate-fade-in-up max-w-6xl mx-auto">
      <div class="bg-gradient-to-r from-purple-500 to-purple-700 p-6 text-white">
        <h2 class="text-xl font-bold">Мои дети</h2>
      </div>
      
      <div class="p-6">
        <!-- Табы для групп -->
        <div class="group-tabs">
          @foreach($groups_data as $index => $group_data)
          <div class="group-tab {{ $loop->first ? 'active' : '' }}" data-tab="group-{{ $group_data['group']->id }}">
            Группа {{ $group_data['group']->name }} ({{ $group_data['children']->count() }})
          </div>
          @endforeach
        </div>
        
        <!-- Контент для каждой группы -->
        @foreach($groups_data as $group_data)
        <div id="group-{{ $group_data['group']->id }}" class="group-content {{ $loop->first ? 'block' : 'hidden' }}">
          <h3 class="text-lg font-semibold mb-4">Дети в группе {{ $group_data['group']->name }}</h3>
          
          <!-- Блок с информацией о воспитателе -->
          @if($group_data['group']->educator)
          <div class="bg-purple-50 p-4 rounded-lg mb-4">
            <h4 class="font-medium">Воспитатель группы:</h4>
            <div class="flex items-center space-x-3 mt-2">
              <div>
                <p class="">
                  {{ $group_data['group']->educator->last_name }} 
                  {{ $group_data['group']->educator->first_name }} 
                  {{ $group_data['group']->educator->patronymic }}
                </p>
                <p class="text-sm">Контакты: {{ $group_data['group']->educator->phone_number }}</p>
              </div>
            </div>
          </div>
          @endif
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            @foreach($group_data['children'] as $child)
            <div class="bg-purple-50 p-4 rounded-lg hover:bg-purple-100 transition">
              <div class="flex items-center space-x-3">
                <div>
                  <h4 class="font-medium">{{ $child->last_name }} {{ $child->first_name }}</h4>
                  <p class="text-sm text-gray-600">Дата рождения: {{ $child->birth_date }}</p>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          
          <h3 class="text-lg font-semibold mb-4">Расписание группы {{ $group_data['group']->name }}</h3>
          @include('partials.group_schedule', ['schedule_items' => $group_data['schedule']])
        </div>
        @endforeach
      </div>
    </div>
@endif
  </div>
</section>
<x-footer/>
<script>
    // Переключение табов групп для родителя
    document.querySelectorAll('.group-tab').forEach(tab => {
      tab.addEventListener('click', function() {
        // Убираем активный класс у всех табов
        document.querySelectorAll('.group-tab').forEach(t => t.classList.remove('active'));
        // Добавляем активный класс текущему табу
        this.classList.add('active');
        
        // Скрываем все контенты групп
        document.querySelectorAll('.group-content').forEach(c => c.classList.add('hidden'));
        // Показываем нужный контент
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.remove('hidden');
      });
    });
  </script>
</body>
</html>