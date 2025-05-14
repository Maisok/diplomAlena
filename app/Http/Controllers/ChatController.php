<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        if ($user->isParent()) {
            $chats = $user->educatorChats()
                ->with(['participant', 'messages' => function($query) {
                    $query->latest()->limit(1);
                }])
                ->get()
                ->sortByDesc(function($chat) {
                    return optional($chat->messages->first())->created_at ?? $chat->created_at;
                });
                
            $adminChat = $user->getAdminChat();
            return view('chats.parent-index', compact('chats', 'adminChat'));
        }
        
        if ($user->isEducator()) {
            $chats = Chat::where('type', 'parent_educator')
                ->where('participant_id', $user->id)
                ->with(['parent', 'parent.children', 'messages' => function($query) {
                    $query->latest()->limit(1);
                }])
                ->get()
                ->sortByDesc(function($chat) {
                    return $chat->lastMessage ? $chat->lastMessage->created_at : $chat->created_at;
                });
            
            return view('chats.educator-index', compact('chats'));
        }
        
        if ($user->isAdmin()) {
            $chats = Chat::where('type', 'parent_admin')
                ->with(['parent', 'messages' => function($query) {
                    $query->latest()->limit(1);
                }])
                ->get()
                ->sortByDesc(function($chat) {
                    return $chat->lastMessage ? $chat->lastMessage->created_at : $chat->created_at;
                });
            
            return view('chats.admin-index', compact('chats'));
        }
        
        abort(403);
    }

    public function show(Chat $chat)
    {
        if (auth()->id() !== $chat->parent_id && auth()->id() !== $chat->participant_id) {
            abort(403);
        }
        
        $chat->load(['messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'messages.sender']);
        
        // Помечаем сообщения как прочитанные
        Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return view('chats.show', compact('chat'));
    }

    public function storeMessage(Request $request, Chat $chat)
    {
        if (auth()->id() !== $chat->parent_id && auth()->id() !== $chat->participant_id) {
            abort(403);
        }
        
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);
        
        $message = $chat->messages()->create([
            'sender_id' => auth()->id(),
            'content' => $request->content,
        ]);
        
        // Всегда возвращаем JSON
        return response()->json([
            'message' => $message->load('sender'),
            'status' => 'success'
        ]);
    }

    public function startWithEducator(User $educator)
    {
        if (!auth()->user()->isParent() || !$educator->isEducator()) {
            abort(403);
        }
        
        $chat = Chat::firstOrCreate([
            'type' => 'parent_educator',
            'parent_id' => auth()->id(),
            'participant_id' => $educator->id,
        ], [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('chats.show', $chat);
    }

    public function startWithAdmin()
    {
        if (!auth()->user()->isParent()) {
            abort(403);
        }
        
        $chat = Chat::firstOrCreate([
            'type' => 'parent_admin',
            'parent_id' => auth()->id(),
            'participant_id' => User::where('status', 'admin')->first()->id,
        ]);
        
        return redirect()->route('chats.show', $chat);
    }

    public function getMessages(Chat $chat, Request $request)
    {
        if (auth()->id() !== $chat->parent_id && auth()->id() !== $chat->participant_id) {
            abort(403);
        }

        $lastMessageId = $request->input('last_message_id', 0);

        $messages = $chat->messages()
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Помечаем сообщения как прочитанные
        if ($messages->isNotEmpty()) {
            Message::where('chat_id', $chat->id)
                ->where('sender_id', '!=', auth()->id())
                ->where('is_read', false)
                ->whereIn('id', $messages->pluck('id'))
                ->update(['is_read' => true]);
        }

        return response()->json([
            'messages' => $messages,
            'last_message_id' => $messages->last()->id ?? $lastMessageId
        ]);
    }

    public function getUpdates()
    {
        $user = auth()->user();
        $chats = collect();
        $adminChat = null;
    
        try {
            if ($user->isParent()) {
                // Чаты с воспитателями
                $educatorChats = Chat::where('type', 'parent_educator')
                    ->where('parent_id', $user->id)
                    ->withCount(['messages as unread_count' => function($query) use ($user) {
                        $query->where('is_read', false)
                            ->where('sender_id', '!=', $user->id);
                    }])
                    ->with(['lastMessage', 'participant'])
                    ->get();
    
                $chats = $chats->merge($educatorChats);
    
                // Чат с администрацией
                $adminChat = Chat::where('type', 'parent_admin')
                    ->where('parent_id', $user->id)
                    ->withCount(['messages as unread_count' => function($query) use ($user) {
                        $query->where('is_read', false)
                            ->where('sender_id', '!=', $user->id);
                    }])
                    ->with(['lastMessage'])
                    ->first();
            }
            elseif ($user->isEducator()) {
                $chats = Chat::where('type', 'parent_educator')
                    ->where('participant_id', $user->id)
                    ->withCount(['messages as unread_count' => function($query) use ($user) {
                        $query->where('is_read', false)
                            ->where('sender_id', '!=', $user->id);
                    }])
                    ->with(['lastMessage', 'parent'])
                    ->get();
            }
            elseif ($user->isAdmin()) {
                $chats = Chat::where('type', 'parent_admin')
                    ->withCount(['messages as unread_count' => function($query) use ($user) {
                        $query->where('is_read', false)
                            ->where('sender_id', '!=', $user->id);
                    }])
                    ->with(['lastMessage', 'parent'])
                    ->get();
            }
    
            $result = $chats->map(function($chat) {
                return [
                    'id' => $chat->id,
                    'unread_count' => $chat->unread_count,
                    'last_message_time' => optional($chat->lastMessage)->created_at,
                    'last_message_content' => optional($chat->lastMessage)->content,
                    'participant_name' => $chat->participant->full_name ?? null,
                    'type' => $chat->type
                ];
            });
    
            $adminChatData = null;
            if ($adminChat) {
                $adminChatData = [
                    'id' => $adminChat->id,
                    'unread_count' => $adminChat->unread_count,
                    'last_message_time' => optional($adminChat->lastMessage)->created_at,
                    'last_message_content' => optional($adminChat->lastMessage)->content,
                    'type' => $adminChat->type,
                    'participant_name' => 'Администрация'
                ];
            }
    
            return response()->json([
                'chats' => $result,
                'admin_chat' => $adminChatData,
                'status' => 'success'
            ]);
    
        } catch (\Exception $e) {
            \Log::error('Error in getUpdates: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching chat updates',
                'error' => $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    public function updateMessageStatus(Request $request, Chat $chat)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'integer'
        ]);
    
        // Обновляем только сообщения, которые принадлежат чату и отправитель не текущий пользователь
        Message::whereIn('id', $request->message_ids)
            ->where('chat_id', $chat->id)
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    
        return response()->json(['status' => 'success']);
    }

    public function markMessagesAsRead(Chat $chat)
    {
        // Получаем ID всех видимых непрочитанных сообщений
        $unreadMessages = $chat->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->get();
    
        if ($unreadMessages->isEmpty()) {
            return response()->json(['status' => 'no-messages']);
        }
    
        // Помечаем сообщения как прочитанные
        $unreadMessages->each->update(['is_read' => true]);
    
        // Отправляем уведомление через Broadcast (если настроено)
        event(new MessagesRead($chat, auth()->id(), $unreadMessages->pluck('id')->toArray()));
    
        return response()->json([
            'status' => 'success',
            'messages' => $unreadMessages
        ]);
    }


    public function checkMessageStatus(Request $request, Chat $chat)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'integer'
        ]);

        // Получаем сообщения, которые были прочитаны
        $readMessages = Message::whereIn('id', $request->message_ids)
            ->where('chat_id', $chat->id)
            ->where('sender_id', auth()->id())
            ->where('is_read', true)
            ->pluck('id')
            ->toArray();

        return response()->json([
            'read_messages' => $readMessages,
            'status' => 'success'
        ]);
    }

    public function deleteMessage(User $user, Message $message)
    {
        // Проверяем права на удаление
        if ($user->isParent()) {
            abort(403, 'У вас нет прав на удаление этого сообщения');
        }

        $message->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Сообщение удалено'
        ]);
    }
}