@extends('layouts.chat')

@section('page-title', 'ЧАТЫ С РОДИТЕЛЯМИ')
@section('page-subtitle', 'Общайтесь с родителями ваших воспитанников')

@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold text-[#4A3F9B] mb-6">Управление чатами</h1>
  
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Поиск и существующие чаты -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('chats.index') }}" class="flex gap-4">
                <div class="relative flex-grow">
                    <input type="text" name="search" placeholder="Поиск по ФИО"
                           value="{{ request('search') }}"
                           class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <button type="submit" class="bg-gradient-to-r from-[#4A3F9B] to-[#D32F2F] text-white px-6 py-3 rounded-lg hover:opacity-90 transition">
                    Найти
                </button>
            </form>
        </div>

        <h2 class="text-xl font-bold text-[#4A3F9B] mb-4">Активные чаты</h2>

        @forelse($chats as $chat)
            <div class="bg-white rounded-xl shadow-md p-5 mb-4 hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        @if($chat->type === 'parent_admin')
                            <h3 class="font-semibold">{{ optional($chat->parent)->full_name }}</h3>
                            <p class="text-gray-600 text-sm">
                                Дети: 
                                @if($chat->parent && $chat->parent->children->isNotEmpty())
                                @foreach($chat->parent->children as $child)
                                    <span>{{ $loop->first ? '' : ', ' }}{{ $child->first_name }}</span>
                                @endforeach
                            @else
                                <span class="text-gray-400">Нет данных</span>
                            @endif
                            </p>
                        @elseif($chat->type === 'admin_educator')
                            <h3 class="font-semibold">{{ optional($chat->participant)->full_name }}</h3>
                            <p class="text-gray-600 text-sm">Воспитатель</p>
                        @endif
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500 last-message-time-{{ $chat->id }} mr-2">
                            {{ optional($chat->messages->first())->created_at ? optional($chat->messages->first())->created_at->diffForHumans() : 'Нет сообщений' }}
                        </span>
                        <span class="chat-badge-{{ $chat->id }} inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full"
                              style="display: {{ $chat->unreadMessagesCount() > 0 ? 'inline-flex' : 'none' }}">
                            {{ $chat->unreadMessagesCount() }}
                        </span>
                    </div>
                </div>

                <p class="text-gray-700 mb-4 last-message-content-{{ $chat->id }}">
                    {{ optional($chat->messages->first())->content ? Str::limit($chat->messages->first()->content, 80) : 'Чат начат' }}
                </p>

                <a href="{{ route('chats.show', $chat) }}" class="text-[#4A3F9B] hover:text-[#D32F2F] font-medium inline-flex items-center transition-colors">
                    Перейти к чату
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <p class="text-gray-500">У вас пока нет активных чатов</p>
            </div>
        @endforelse
    </div>

    <!-- Список воспитателей для нового чата -->
    <div>
        <div class="bg-white rounded-xl shadow-md p-6 sticky top-4">
            <h2 class="text-xl font-bold text-[#4A3F9B] mb-4">Начать новый чат</h2>

            @if($educatorsWithoutChat->isNotEmpty())
                <ul class="space-y-3">
                    @foreach($educatorsWithoutChat as $educator)
                        <li class="p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-medium">{{ $educator->full_name }}</h3>
                                    <p class="text-sm text-gray-500">ID: {{ $educator->id }}</p>
                                </div>
                                <a href="{{ route('chats.startWithEducatorFromAdmin', $educator) }}"
                                   class="px-4 py-2 bg-[#4A3F9B] text-white rounded-lg hover:bg-[#D32F2F] transition text-sm">
                                    Чат
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-center py-4">У вас уже есть чаты со всеми воспитателями</p>
            @endif
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateInterval = 3000;
    let updateTimer;

    function fetchChatUpdates() {
        fetch('{{ route("chats.updates") }}', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateChatsUI(data.chats);
                updateAdminChatUI(data.admin_chat);
                updateTabTitle(data.chats, data.admin_chat);
            }
            scheduleNextUpdate();
        })
        .catch(error => {
            console.error('Ошибка обновления чатов:', error);
            scheduleNextUpdate(10000);
        });
    }

    function updateChatsUI(chats) {
        chats.forEach(chat => {
            const badge = document.querySelector(`.chat-badge-${chat.id}`);
            if (badge) {
                badge.textContent = chat.unread_count;
                badge.style.display = chat.unread_count > 0 ? '' : 'none';
            }

            const timeElement = document.querySelector(`.last-message-time-${chat.id}`);
            if (timeElement && chat.last_message_time) {
                timeElement.textContent = formatMessageTime(chat.last_message_time);
            }

            const contentElement = document.querySelector(`.last-message-content-${chat.id}`);
            if (contentElement && chat.last_message_content) {
                contentElement.textContent = chat.last_message_content;
            }
        });
    }

    function updateAdminChatUI(adminChat) {
        if (!adminChat) return;

        const adminBadge = document.querySelector(`.chat-badge-${adminChat.id}`);
        if (adminBadge) {
            adminBadge.textContent = adminChat.unread_count;
            adminBadge.style.display = adminChat.unread_count > 0 ? 'inline-block' : 'none';
        }

        const adminTime = document.querySelector(`.last-message-time-${adminChat.id}`);
        if (adminTime && adminChat.last_message_time) {
            adminTime.textContent = formatMessageTime(adminChat.last_message_time);
        }

        const adminContent = document.querySelector(`.last-message-content-${adminChat.id}`);
        if (adminContent && adminChat.last_message_content) {
            adminContent.textContent = adminChat.last_message_content;
        }
    }

    function formatMessageTime(timestamp) {
        const now = new Date();
        const messageTime = new Date(timestamp);
        const diff = Math.floor((now - messageTime) / 1000);

        if (diff < 60) return 'только что';
        if (diff < 3600) return `${Math.floor(diff/60)} мин. назад`;
        if (diff < 86400) return `${Math.floor(diff/3600)} ч. назад`;
        
        return messageTime.toLocaleString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function updateTabTitle(chats, adminChat) {
        let totalUnread = chats.reduce((sum, chat) => sum + chat.unread_count, 0);
        if (adminChat) {
            totalUnread += adminChat.unread_count;
        }
        document.title = totalUnread > 0 ? `(${totalUnread}) 7 звезд` : '7 звезд';
    }

    function scheduleNextUpdate(delay = updateInterval) {
        clearTimeout(updateTimer);
        updateTimer = setTimeout(fetchChatUpdates, delay);
    }

    fetchChatUpdates();

    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            clearTimeout(updateTimer);
        } else {
            fetchChatUpdates();
        }
    });
});
</script>
@endsection