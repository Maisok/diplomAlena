@extends('layouts.chat')

@section('page-title', 'МОИ ЧАТЫ')
@section('page-subtitle', 'Общайтесь с воспитателями и администрацией')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
  <h1 class="text-2xl font-bold text-[#4A3F9B] mb-8">Мои чаты</h1>
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
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Чат с администрацией -->
      <div class="bg-white rounded-xl shadow-lg p-6">
          <div class="flex items-center mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#4A3F9B] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <h2 class="text-xl font-semibold">Чат с администрацией</h2>
          </div>
          
          @if($adminChat)
              <div class="mb-6">
                  <div class="flex justify-between items-center mb-2">
                      <p class="text-sm text-gray-500 last-message-time-{{ $adminChat->id }}">
                          {{ $adminChat->lastMessage ? $adminChat->lastMessage->created_at->diffForHumans() : 'нет сообщений' }}
                      </p>
                      <span class="chat-badge-{{ $adminChat->id }} bg-red-500 text-white text-xs px-2 py-1 rounded-full"
                            style="display: {{ $adminChat->unreadMessagesCount() > 0 ? 'inline-block' : 'none' }}">
                          {{ $adminChat->unreadMessagesCount() }}
                      </span>
                  </div>
                  <p class="text-gray-700 last-message-content-{{ $adminChat->id }}">
                      {{ $adminChat->lastMessage ? Str::limit($adminChat->lastMessage->content, 100) : 'Чат начат' }}
                  </p>
              </div>
              <a href="{{ route('chats.show', $adminChat) }}" class="w-full bg-gradient-to-r from-[#4A3F9B] to-[#D32F2F] text-white px-6 py-3 rounded-lg hover:opacity-90 transition flex items-center justify-center">
                  Открыть чат
              </a>
          @else
              <form action="{{ route('chats.start.admin') }}" method="POST" class="mt-4">
                  @csrf
                  <button type="submit" class="w-full bg-gradient-to-r from-[#4A3F9B] to-[#D32F2F] text-white px-6 py-3 rounded-lg hover:opacity-90 transition">
                      Начать чат с администрацией
                  </button>
              </form>
          @endif
      </div>
      
      <!-- Чаты с воспитателями -->
      <div class="bg-white rounded-xl shadow-lg p-6">
          <div class="flex items-center mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#4A3F9B] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <h2 class="text-xl font-semibold">Чаты с воспитателями</h2>
          </div>
          
          @if($chats->isNotEmpty())
              <div class="space-y-4 mb-6">
                  @foreach($chats as $chat)
                      <div class="p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                          <div class="flex justify-between items-start">
                              <div>
                                  <h3 class="font-medium">{{ $chat->participant->full_name }}</h3>
                                  <p class="text-gray-600 text-sm last-message-content-{{ $chat->id }}">
                                      {{ $chat->lastMessage ? Str::limit($chat->lastMessage->content, 50) : 'Нет сообщений' }}
                                  </p>
                              </div>
                              <div class="flex items-center">
                                  <span class="text-gray-500 text-xs last-message-time-{{ $chat->id }} mr-2">
                                      {{ $chat->lastMessage ? $chat->lastMessage->created_at->diffForHumans() : '' }}
                                  </span>
                                  <span class="chat-badge-{{ $chat->id }} bg-red-500 text-white text-xs px-2 py-1 rounded-full"
                                        style="display: {{ $chat->unreadMessagesCount() > 0 ? 'inline-block' : 'none' }}">
                                      {{ $chat->unreadMessagesCount() }}
                                  </span>
                              </div>
                          </div>
                          <a href="{{ route('chats.show', $chat) }}" class="text-[#4A3F9B] hover:text-[#D32F2F] text-sm inline-flex items-center mt-2">
                              Открыть
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                              </svg>
                          </a>
                      </div>
                  @endforeach
              </div>
          @else
              <p class="text-gray-500 mb-6">У вас пока нет чатов с воспитателями</p>
          @endif
          
          <a href="{{ route('educators.list') }}" class="w-full bg-gradient-to-r from-[#4A3F9B] to-[#D32F2F] text-white px-6 py-3 rounded-lg hover:opacity-90 transition flex items-center justify-center">
              Начать новый чат
          </a>
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
                updateAllChatsUI(data.chats, data.admin_chat);
                updateTabTitle(data.chats, data.admin_chat);
            }
            scheduleNextUpdate();
        })
        .catch(error => {
            console.error('Ошибка обновления чатов:', error);
            scheduleNextUpdate(10000);
        });
    }

    function updateAllChatsUI(chats, adminChat) {
        if (chats && chats.length > 0) {
            chats.forEach(chat => {
                updateChatUI(chat);
            });
        }

        if (adminChat) {
            updateChatUI(adminChat);
        }
    }

    function updateChatUI(chat) {
        const badge = document.querySelector(`.chat-badge-${chat.id}`);
        if (badge) {
            badge.textContent = chat.unread_count;
            badge.style.display = chat.unread_count > 0 ? 'inline-block' : 'none';
        }

        const timeElement = document.querySelector(`.last-message-time-${chat.id}`);
        if (timeElement && chat.last_message_time) {
            timeElement.textContent = formatMessageTime(chat.last_message_time);
        }

        const contentElement = document.querySelector(`.last-message-content-${chat.id}`);
        if (contentElement) {
            contentElement.textContent = chat.last_message_content || 'Нет сообщений';
        }

        if (chat.participant_name) {
            const nameElement = document.querySelector(`.participant-name-${chat.id}`);
            if (nameElement) {
                nameElement.textContent = chat.participant_name;
            }
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
        let totalUnread = chats ? chats.reduce((sum, chat) => sum + (chat.unread_count || 0), 0) : 0;
        if (adminChat) {
            totalUnread += adminChat.unread_count || 0;
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