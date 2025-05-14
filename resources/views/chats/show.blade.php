@extends('layouts.chat')

@section('page-title', 'ЧАТ')
@section('page-subtitle', 'Общение с ' . ($chat->participant->full_name ?? 'администрацией'))

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg hover-scale animate-fade-in-up">
  <div class="p-6 border-b border-gray-200">
    <h2 class="text-xl font-semibold flex items-center">
      @if(auth()->user()->isParent())
        <svg class="w-6 h-6 text-[#4A3F9B] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        {{ $chat->participant->full_name }}
      @else
        <svg class="w-6 h-6 text-[#D32F2F] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        {{ $chat->parent->full_name }}
      @endif
    </h2>
  </div>
  
  <div class="h-96 overflow-y-auto p-6" id="messages-container">
    @foreach($chat->messages as $message)
    <div class="mb-4 @if($message->sender_id == auth()->id()) text-right @endif">
      <div class="@if($message->sender_id == auth()->id()) bg-purple-100 @else bg-gray-100 @endif inline-block rounded-lg px-4 py-2 max-w-xs md:max-w-md">
        <p>{{ $message->content }}</p>
        <p class="text-xs text-gray-500 mt-1">
          {{ $message->created_at->format('d.m.Y H:i') }}
          @if($message->sender_id == auth()->id())
            @if($message->is_read)
              <span class="text-green-500">✓ Прочитано</span>
            @else
              <span class="text-gray-500">✓ Отправлено</span>
            @endif
          @endif
        </p>
      </div>
    </div>
    @endforeach
  </div>
  
  <div class="p-6 border-t border-gray-200">
    <form id="message-form" action="{{ route('chats.messages.store', $chat) }}" method="POST">
      @csrf
      <div class="flex">
        <input type="text" name="content" id="message-input" maxlength="2000"
               placeholder="Напишите сообщение..." 
               class="flex-1 border rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
        <button type="submit" class="bg-gradient-to-r from-[#4A3F9B] to-[#D32F2F] text-white px-6 py-2 rounded-r-lg hover:opacity-90 transition">
          Отправить
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messages-container');
    const form = document.getElementById('message-form');
    const input = document.getElementById('message-input');
    let lastMessageId = {{ $chat->messages->last()->id ?? 0 }};
    let isPolling = true;
    let myUnreadMessages = [];
    let processedMessageIds = new Set();

    // Инициализация: сохраняем ID всех текущих сообщений
    @foreach($chat->messages as $message)
        processedMessageIds.add({{ $message->id }});
    @endforeach

    function scrollToBottom() {
        container.scrollTop = container.scrollHeight;
    }

    function addMessage(message, isNew = true) {
        // Проверяем, не обрабатывали ли мы уже это сообщение
        if (processedMessageIds.has(message.id)) {
            return;
        }
        
        processedMessageIds.add(message.id);
        
        const isCurrentUser = message.sender_id == {{ auth()->id() }};
        const messageDiv = document.createElement('div');
        messageDiv.className = `mb-4 ${isCurrentUser ? 'text-right' : ''}`;
        messageDiv.id = `message-${message.id}`;
        
        const messageDate = new Date(message.created_at);
        const formattedDate = messageDate.toLocaleString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        const statusHtml = isCurrentUser 
            ? `<span class="${message.is_read ? 'text-green-500' : 'text-gray-500'}">✓ ${message.is_read ? 'Прочитано' : 'Отправлено'}</span>`
            : '';

        messageDiv.innerHTML = `
            <div class="${isCurrentUser ? 'bg-purple-100' : 'bg-gray-100'} inline-block rounded-lg px-4 py-2 max-w-xs md:max-w-md">
                <p>${message.content}</p>
                <p class="text-xs text-gray-500 mt-1">
                    ${formattedDate} ${statusHtml}
                </p>
            </div>
        `;

        if (isNew) {
            container.appendChild(messageDiv);
        } else {
            const existing = document.getElementById(`message-${message.id}`);
            if (existing) {
                existing.replaceWith(messageDiv);
            } else {
                container.appendChild(messageDiv);
            }
        }

        if (isCurrentUser && !message.is_read) {
            myUnreadMessages.push(message.id);
        }

        scrollToBottom();
    }

    function checkMessageStatuses() {
        if (myUnreadMessages.length === 0) return;

        fetch(`/chats/{{ $chat->id }}/check-message-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message_ids: myUnreadMessages })
        })
        .then(response => response.json())
        .then(data => {
            if (data.read_messages && data.read_messages.length > 0) {
                data.read_messages.forEach(id => {
                    const statusSpan = document.querySelector(`#message-${id} .text-gray-500`);
                    if (statusSpan) {
                        statusSpan.classList.replace('text-gray-500', 'text-green-500');
                        statusSpan.textContent = '✓ Прочитано';
                    }
                });
                myUnreadMessages = myUnreadMessages.filter(msgId => !data.read_messages.includes(msgId));
            }
        })
        .catch(console.error);
    }

    function checkNewMessages() {
        if (!isPolling) return;
        
        fetch(`/chats/{{ $chat->id }}/messages?last_message_id=${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
            if (data.messages?.length) {
                data.messages.forEach(msg => {
                    // Добавляем только новые сообщения
                    if (!processedMessageIds.has(msg.id)) {
                        addMessage(msg);
                        lastMessageId = msg.id;
                    }
                });
            }
            
            checkMessageStatuses();
            
            setTimeout(checkNewMessages, 2000);
        })
        .catch(error => {
            console.error('Ошибка при получении сообщений:', error);
            setTimeout(checkNewMessages, 2000);
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const content = input.value.trim();
        if (!content) return;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ content })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                addMessage(data.message);
                lastMessageId = data.message.id;
                input.value = '';
            }
        })
        .catch(console.error);
    });

    // Инициализация существующих сообщений
    @foreach($chat->messages as $message)
        @if($message->sender_id == auth()->id() && !$message->is_read)
            myUnreadMessages.push({{ $message->id }});
        @endif
    @endforeach

    scrollToBottom();
    checkNewMessages();

    document.addEventListener('visibilitychange', function() {
        isPolling = !document.hidden;
        if (isPolling) checkNewMessages();
    });
});
</script>
@endsection