@vite('resources/css/app.css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<nav class="flex items-center justify-between p-4 bg-[#5543AE] shadow-lg sticky top-0 z-50">
    
    <!-- Логотип с анимацией -->
    <div class="flex items-center">
        <a href="{{route('home')}}" class="hover:scale-105 transition-transform duration-300">
            <img alt="Logo with a purple spaceship and a yellow star" src="{{ asset('images/logo.png') }}" class="h-12 md:h-14"/>
        </a>
    </div>

    <!-- Основное меню для десктопа -->
    <div class="hidden md:flex space-x-6 lg:space-x-8 xl:space-x-10 items-center">
        @if(auth()->check())
        <a class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:bg-[#6a56c5]" href="{{route('shownews.index')}}">
            НОВОСТИ
        </a>
        @endif
        
        <a class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:bg-[#6a56c5]" href="{{route('about')}}">
            РОДИТЕЛЯМ
        </a>
        
        <a class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:bg-[#6a56c5]" href="{{route('certificates.display')}}">
            ЛИЦЕНЗИИ
        </a>
        
        <a class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:bg-[#6a56c5]" href="{{route('company')}}">
            О НАС
        </a>

        @if(auth()->check())
            <a class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:bg-[#6a56c5] relative" href="{{ route('chats.index') }}">
                ЧАТЫ
                <span id="global-chat-badge" class="hidden absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">0</span>
            </a>
        @endif
        
        @if(auth()->check())
            @if(auth()->user()->isAdmin())
                <a class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:bg-[#6a56c5] bg-[#7a66d4]" href="{{ route('admin.dashboard') }}">
                    АДМИН
                </a>
            @endif
            <a class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:bg-[#6a56c5] flex items-center" href="{{ route('profile') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                КАБИНЕТ
            </a>
        @else
            <a class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:bg-[#6a56c5] border border-white" href="{{ route('login') }}">
                ВОЙТИ
            </a>
        @endif
    </div>

    <!-- Кнопка мобильного меню -->
    <div class="md:hidden">
        <button id="mobile-menu-button" class="text-white p-2 rounded-md hover:bg-[#6a56c5] transition-all duration-300 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
</nav>

<!-- Мобильное меню с анимацией -->
<div id="mobile-menu" class="hidden md:hidden bg-[#5543AE] text-white shadow-xl transform origin-top transition-all duration-300 ease-out">
    <div class="flex flex-col items-stretch space-y-1 p-2">
        @if(auth()->check())
        <a class="text-white hover:bg-[#6a56c5] px-4 py-3 rounded-md text-base font-medium transition-all duration-300" href="{{route('shownews.index')}}">
            НОВОСТИ
        </a>
        @endif
        
        <a class="text-white hover:bg-[#6a56c5] px-4 py-3 rounded-md text-base font-medium transition-all duration-300" href="{{route('about')}}">
            РОДИТЕЛЯМ
        </a>
        
        <a class="text-white hover:bg-[#6a56c5] px-4 py-3 rounded-md text-base font-medium transition-all duration-300" href="{{route('certificates.display')}}">
            ЛИЦЕНЗИИ
        </a>
        
        <a class="text-white hover:bg-[#6a56c5] px-4 py-3 rounded-md text-base font-medium transition-all duration-300" href="{{route('company')}}">
            О НАС
        </a>
        
        @if(auth()->check())
            <a class="text-white hover:bg-[#6a56c5] px-4 py-3 rounded-md text-base font-medium transition-all duration-300 relative" href="{{ route('chats.index') }}">
                ЧАТЫ
                <span id="mobile-chat-badge" class="hidden absolute top-3 right-4 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">0</span>
            </a>
            
            @if(auth()->user()->isAdmin())
                <a class="text-white hover:bg-[#6a56c5] px-4 py-3 rounded-md text-base font-medium transition-all duration-300 bg-[#7a66d4]" href="{{ route('admin.dashboard') }}">
                    ДАШБОРД АДМИНА
                </a>
            @endif
            
            <a class="text-white hover:bg-[#6a56c5] px-4 py-3 rounded-md text-base font-medium transition-all duration-300 flex items-center" href="{{ route('profile') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                ЛИЧНЫЙ КАБИНЕТ
            </a>
        @else
            <a class="text-white hover:bg-[#6a56c5] px-4 py-3 rounded-md text-base font-medium transition-all duration-300 border border-white text-center" href="{{ route('login') }}">
                ВОЙТИ
            </a>
        @endif
    </div>
</div>

<script>
    // Анимация мобильного меню
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            menu.classList.add('scale-y-100', 'opacity-100');
            menu.classList.remove('scale-y-0', 'opacity-0');
        } else {
            menu.classList.add('scale-y-0', 'opacity-0');
            menu.classList.remove('scale-y-100', 'opacity-100');
            setTimeout(() => menu.classList.add('hidden'), 300);
        }
    });

    // Функция для обновления бейджа чатов
    function updateGlobalChatBadge(totalUnread) {
        const desktopBadge = document.getElementById('global-chat-badge');
        const mobileBadge = document.getElementById('mobile-chat-badge');
        
        if (totalUnread > 0) {
            if (desktopBadge) {
                desktopBadge.classList.remove('hidden');
                desktopBadge.textContent = totalUnread > 9 ? '9+' : totalUnread;
            }
            if (mobileBadge) {
                mobileBadge.classList.remove('hidden');
                mobileBadge.textContent = totalUnread > 9 ? '9+' : totalUnread;
            }
        } else {
            if (desktopBadge) desktopBadge.classList.add('hidden');
            if (mobileBadge) mobileBadge.classList.add('hidden');
        }
    }

    // Функция для проверки новых сообщений
    function checkForNewMessages() {
        fetch('{{ route("chats.updates") }}', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                let totalUnread = 0;
                
                // Суммируем непрочитанные сообщения из всех чатов
                if (data.chats) {
                    totalUnread += data.chats.reduce((sum, chat) => sum + (chat.unread_count || 0), 0);
                }
                
                // Добавляем непрочитанные из чата с администрацией
                if (data.admin_chat && data.admin_chat.unread_count) {
                    totalUnread += data.admin_chat.unread_count;
                }
                
                // Обновляем бейдж
                updateGlobalChatBadge(totalUnread);
                
                // Обновляем заголовок вкладки
                document.title = totalUnread > 0 ? `(${totalUnread}) 7 звезд` : '7 звезд';
            }
            
            // Повторяем проверку через 5 секунд
            setTimeout(checkForNewMessages, 5000);
        })
        .catch(error => {
            console.error('Ошибка при проверке сообщений:', error);
            setTimeout(checkForNewMessages, 10000);
        });
    }

    // Запускаем проверку при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        checkForNewMessages();
        
        // Закрываем мобильное меню при клике на пункт
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });
    });
</script>