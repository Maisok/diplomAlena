@extends('layouts.chat')

@section('page-title', 'ЧАТЫ С РОДИТЕЛЯМИ И АДМИНИСТРАЦИЕЙ')
@section('page-subtitle', 'Общение с родителями и администрацией')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-[#4A3F9B] mb-6">Чаты с родителями</h1>
    
    <!-- Поисковая строка -->
    <div class="mb-8">
        <form method="GET" action="{{ route('chats.index') }}" class="flex gap-4">
            <div class="relative flex-grow">
                <input type="text" name="search" placeholder="Поиск по ФИО родителя или ребенка"
                       value="{{ request('search') }}"
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <button type="submit" class="bg-gradient-to-r from-[#4A3F9B] to-[#D32F2F] text-white px-6 py-3 rounded-lg hover:opacity-90 transition">
                Найти
            </button>
        </form>
    </div>

    <!-- Список чатов -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($chats as $chat)
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-5">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-[#4A3F9B]">{{ optional($chat->parent)->full_name }}</h3>
                        <p class="text-gray-600 text-sm">
                            Ребенок: {{ optional(optional($chat->parent)->children->first())->full_name ?? 'Не указан' }}
                        </p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500 last-message-time-{{ $chat->id }} mr-2">
                            {{ optional($chat->lastMessage)->created_at ? $chat->lastMessage->created_at->diffForHumans() : 'Нет сообщений' }}
                        </span>
                        <span class="chat-badge-{{ $chat->id }} inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full"
                              style="display: {{ $chat->unreadMessagesCount() > 0 ? 'inline-flex' : 'none' }}">
                            {{ $chat->unreadMessagesCount() }}
                        </span>
                    </div>
                </div>
                
                <p class="text-gray-700 mb-4 last-message-content-{{ $chat->id }}">
                    {{ optional($chat->lastMessage)->content ? Str::limit($chat->lastMessage->content, 80) : 'Чат начат' }}
                </p>
                
                <a href="{{ route('chats.show', $chat) }}" 
                   class="text-[#4A3F9B] hover:text-[#D32F2F] font-medium inline-flex items-center transition-colors">
                    Перейти к чату
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-xl shadow-md p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-700 mb-2">Нет активных чатов</h3>
                <p class="text-gray-500">У вас пока нет чатов с родителями</p>
            </div>
        @endforelse
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateInterval = 3000;
    let updateTimer;
    let processedMessageIds = new Set();

    // Инициализация: сохраняем ID всех текущих сообщений
    @foreach($chats as $chat)
        @if($chat->lastMessage)
            processedMessageIds.add({{ $chat->lastMessage->id }});
        @endif
    @endforeach

    @if(isset($adminChat) && $adminChat?->lastMessage)
        processedMessageIds.add({{ $adminChat->lastMessage->id }});
    @endif

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
            if (chat.last_message_id && !processedMessageIds.has(chat.last_message_id)) {
                processedMessageIds.add(chat.last_message_id);

                const badge = document.querySelector(`.chat-badge-${chat.id}`);
                if (badge) {
                    badge.textContent = chat.unread_count;
                    badge.style.display = chat.unread_count > 0 ? 'inline-flex' : 'none';
                }

                const timeElement = document.querySelector(`.last-message-time-${chat.id}`);
                if (timeElement && chat.last_message_time) {
                    timeElement.textContent = formatMessageTime(chat.last_message_time);
                }

                const contentElement = document.querySelector(`.last-message-content-${chat.id}`);
                if (contentElement && chat.last_message_content) {
                    contentElement.textContent = chat.last_message_content;
                }
            }
        });
    }

    function updateAdminChatUI(adminChat) {
        if (!adminChat) return;

        if (adminChat.last_message_id && !processedMessageIds.has(adminChat.last_message_id)) {
            processedMessageIds.add(adminChat.last_message_id);

            const adminBadge = document.querySelector('.chat-badge-admin');
            if (adminBadge) {
                adminBadge.textContent = adminChat.unread_count;
                adminBadge.style.display = adminChat.unread_count > 0 ? 'inline-flex' : 'none';
            }

            const adminTime = document.querySelector('.last-message-time-admin');
            if (adminTime && adminChat.last_message_time) {
                adminTime.textContent = formatMessageTime(adminChat.last_message_time);
            }

            const adminContent = document.querySelector('.last-message-content-admin');
            if (adminContent && adminChat.last_message_content) {
                adminContent.textContent = adminChat.last_message_content;
            }
        }
    }

    function formatMessageTime(timestamp) {
        const now = new Date();
        const messageTime = new Date(timestamp);
        const diff = Math.floor((now - messageTime) / 1000);

        if (diff < 60) return 'только что';
        if (diff < 3600) return `${Math.floor(diff / 60)} мин. назад`;
        if (diff < 86400) return `${Math.floor(diff / 3600)} ч. назад`;

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
        document.title = totalUnread > 0 ? `(${totalUnread}) 7 звёзд` : '7 звёзд';
    }

    function scheduleNextUpdate(delay = updateInterval) {
        clearTimeout(updateTimer);
        updateTimer = setTimeout(fetchChatUpdates, delay);
    }

    fetchChatUpdates();

    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            clearTimeout(updateTimer);
        } else {
            fetchChatUpdates();
        }
    });
});
</script>

@endsection