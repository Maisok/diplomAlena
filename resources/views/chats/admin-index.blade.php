@extends('layouts.chat')

@section('page-title', 'ЧАТЫ С РОДИТЕЛЯМИ')
@section('page-subtitle', 'Общайтесь с родителями ваших воспитанников')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg hover-scale animate-fade-in-up">
  <div class="p-6">
    <div class="grid grid-cols-1 gap-6" id="parent-chats-list">
      @forelse($chats as $chat)
        <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0 chat-item" data-chat-id="{{ $chat->id }}">
          <div class="flex justify-between items-start">
            <div>
              <h2 class="text-lg font-semibold">{{ $chat->parent->full_name }}</h2>
              <p class="text-gray-600 text-sm mt-1">
                Ребенок: {{ $chat->parent->children->first()->full_name ?? 'Не указан' }}
              </p>
            </div>
            
            <div class="text-right">
              <p class="text-sm text-gray-500 last-message-time-{{ $chat->id }}">
                {{ $chat->lastMessage ? $chat->lastMessage->created_at->diffForHumans() : 'Нет сообщений' }}
              </p>
              <span class="chat-badge-{{ $chat->id }} inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full"
                    style="display: {{ $chat->unreadMessagesCount() > 0 ? 'inline-flex' : 'none' }}">
                {{ $chat->unreadMessagesCount() }}
              </span>
            </div>
          </div>
          
          <div class="mt-4">
            <p class="text-gray-700 last-message-content-{{ $chat->id }}">
              {{ $chat->lastMessage ? Str::limit($chat->lastMessage->content, 100) : 'Чат начат' }}
            </p>
          </div>
          
          <div class="mt-4">
            <a href="{{ route('chats.show', $chat) }}" class="text-purple-600 hover:text-purple-800 font-medium inline-flex items-center">
              Перейти к чату
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>
      @empty
        <div class="text-center py-8">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
          <p class="text-gray-500">У вас пока нет активных чатов с родителями</p>
        </div>
      @endforelse
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
                badge.style.display = chat.unread_count > 0 ? 'inline-block' : 'none';
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