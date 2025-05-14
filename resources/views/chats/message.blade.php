<div class="mb-4 @if($message->sender_id == auth()->id()) text-right @endif">
    <div class="@if($message->sender_id == auth()->id()) bg-purple-100 @else bg-gray-100 @endif inline-block rounded-lg px-4 py-2">
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