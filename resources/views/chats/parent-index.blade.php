@extends('layouts.chat')

@section('page-title', 'МОИ ЧАТЫ')
@section('page-subtitle', 'Общайтесь с воспитателями и администрацией')

@section('content')
<div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
  <!-- Чат с администрацией -->
  <div class="bg-white rounded-xl shadow-lg hover-scale animate-fade-in-up">
    <div class="p-6">
      <h2 class="text-xl font-semibold mb-4 text-[#4A3F9B]">Чат с администрацией</h2>
      
      @if($adminChat)
      <div class="mb-6">
        <p class="text-gray-600 mb-2">
          Последнее сообщение: 
          <span class="last-message-time-{{ $adminChat->id }}">
            {{ $adminChat->lastMessage ? $adminChat->lastMessage->created_at->diffForHumans() : 'нет сообщений' }}
          </span>
        </p>
        <p class="last-message-content-{{ $adminChat->id }}">
          {{ $adminChat->lastMessage ? Str::limit($adminChat->lastMessage->content, 100) : 'Чат начат' }}
        </p>
        <span class="chat-badge-{{ $adminChat->id }} bg-red-500 text-white text-xs px-2 py-1 rounded-full mt-2 inline-block"
              style="display: {{ $adminChat->unreadMessagesCount() > 0 ? 'inline-block' : 'none' }}">
          {{ $adminChat->unreadMessagesCount() }}
        </span>
      </div>
      <a href="{{ route('chats.show', $adminChat) }}" class="w-full bg-gradient-to-r from-[#4A3F9B] to-[#D32F2F] text-white px-6 py-3 rounded-lg hover:opacity-90 transition flex items-center justify-center">
        Открыть чат
      </a>
      @else
      <form action="{{ route('chats.start.admin') }}" method="POST">
        @csrf
        <button type="submit" class="w-full bg-gradient-to-r from-[#4A3F9B] to-[#D32F2F] text-white px-6 py-3 rounded-lg hover:opacity-90 transition">
          Начать чат с администрацией
        </button>
      </form>
      @endif
    </div>
  </div>
  
  <!-- Чаты с воспитателями -->
  <div class="bg-white rounded-xl shadow-lg hover-scale animate-fade-in-up" style="animation-delay: 0.2s;">
    <div class="p-6">
      <h2 class="text-xl font-semibold mb-4 text-[#4A3F9B]">Чаты с воспитателями</h2>
      
      <div id="educator-chats-list" class="space-y-4">
        @foreach($chats as $chat)
          <div class="p-4 border border-gray-100 rounded-lg chat-item" data-chat-id="{{ $chat->id }}">
            <h3 class="font-medium">{{ $chat->participant->full_name }}</h3>
            <p class="text-gray-600 text-sm last-message-content-{{ $chat->id }}">
              {{ $chat->lastMessage ? Str::limit($chat->lastMessage->content, 50) : 'Нет сообщений' }}
            </p>
            <div class="flex justify-between items-center mt-2">
              <p class="text-gray-500 text-xs last-message-time-{{ $chat->id }}">
                {{ $chat->lastMessage ? $chat->lastMessage->created_at->diffForHumans() : '' }}
              </p>
              <span class="chat-badge-{{ $chat->id }} bg-red-500 text-white text-xs px-2 py-1 rounded-full"
                  style="display: {{ $chat->unreadMessagesCount() > 0 ? 'inline-block' : 'none' }}">
                {{ $chat->unreadMessagesCount() }}
              </span>
            </div>
            <a href="{{ route('chats.show', $chat) }}" class="text-purple-600 hover:text-purple-800 text-sm inline-flex items-center mt-2">
              Открыть
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        @endforeach
      </div>
      
      <div class="mt-6">
        <a href="{{ route('educators.list') }}" class="w-full bg-gradient-to-r from-[#4A3F9B] to-[#D32F2F] text-white px-6 py-3 rounded-lg hover:opacity-90 transition flex items-center justify-center">
          Начать новый чат с воспитателем
        </a>
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