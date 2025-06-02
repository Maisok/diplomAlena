<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChatController extends Controller
{


    public function index(Request $request)
    {
        $user = auth()->user();
        $searchQuery = $request->input('search');
    
        if ($user->isParent()) {
            // --- Логика для родителя ---
            $chatsQuery = Chat::where('type', 'parent_educator')
                ->where('parent_id', $user->id);
    
            if ($searchQuery) {
                $chatsQuery->whereHas('participant', function($query) use ($searchQuery) {
                    $query->where(DB::raw("CONCAT(last_name, ' ', first_name, ' ', patronymic)"), 'like', "%$searchQuery%");
                });
            }
    
            $chats = $chatsQuery
                ->with(['participant', 'messages' => function($query) {
                    $query->latest()->limit(1);
                }])
                ->get()
                ->sortByDesc(function($chat) {
                    return optional($chat->messages->first())->created_at ?? $chat->created_at;
                });
    
            $adminChat = $user->getAdminChat();
            return view('chats.parent-index', compact('chats', 'adminChat', 'searchQuery'));
        }
    
        if ($user->isEducator()) {
            // --- Логика для воспитателя ---
            $chatsQuery = Chat::where('type', 'parent_educator')
                ->where('participant_id', $user->id);
    
            if ($searchQuery) {
                $chatsQuery->whereHas('parent', function($query) use ($searchQuery) {
                    $query->where(DB::raw("CONCAT(last_name, ' ', first_name, ' ', patronymic)"), 'like', "%$searchQuery%");
                });
            }
    
            $chats = $chatsQuery
                ->with(['parent', 'parent.children', 'messages' => function($query) {
                    $query->latest()->limit(1);
                }])
                ->get()
                ->sortByDesc(function($chat) {
                    return optional($chat->lastMessage)->created_at ?? $chat->created_at;
                });
    
            $adminChat = Chat::where('type', 'admin_educator')
                ->where('participant_id', $user->id)
                ->with(['lastMessage'])
                ->first();
    
            return view('chats.educator-index', compact('chats', 'adminChat', 'searchQuery'));
        }
    
        if ($user->isAdmin()) {
            // Все чаты: родители и воспитатели
            $chatsQuery = Chat::where('type', 'parent_admin')
                ->orWhere(function($q) use ($user) {
                    $q->where('type', 'admin_educator')->where('admin_id', $user->id);
                });
    
            // Поиск по имени (родителей или воспитателей)
            if ($searchQuery) {
                $chatsQuery->where(function($q) use ($searchQuery) {
                    $q->whereHas('parent', function($query) use ($searchQuery) {
                            $query->where(DB::raw("CONCAT(last_name, ' ', first_name, ' ', patronymic)"), 'like', "%$searchQuery%");
                        })
                        ->orWhereHas('participant', function($query) use ($searchQuery) {
                            $query->where(DB::raw("CONCAT(last_name, ' ', first_name, ' ', patronymic)"), 'like', "%$searchQuery%");
                        });
                });
            }
    
            $chats = $chatsQuery
            ->with([
                'parent' => function ($query) {
                    $query->with('children');
                },
                'participant',
                'messages' => function($query) {
                    $query->latest()->limit(1);
                }
            ])
            ->get();
    
            // Список воспитателей без чата
            $allEducators = User::where('status', 'educator')->get();
    
            $educatorsWithoutChat = $allEducators->filter(function($educator) use ($user) {
                return !Chat::where('type', 'admin_educator')
                    ->where('admin_id', $user->id)
                    ->where('participant_id', $educator->id)
                    ->exists();
            });
    
            // Фильтр списка воспитателей без чата
            if ($searchQuery) {
                $educatorsWithoutChat = $educatorsWithoutChat->filter(function($educator) use ($searchQuery) {
                    return (
                        stripos($educator->last_name, $searchQuery) !== false ||
                        stripos($educator->first_name, $searchQuery) !== false ||
                        stripos($educator->patronymic, $searchQuery) !== false
                    );
                });
            }
    
            return view('chats.admin-index', compact('chats', 'educatorsWithoutChat', 'searchQuery'));
        }
    
        abort(403);
    }

    public function startWithEducatorFromAdmin(User $educator)
    {
        $admin = auth()->user();
        if (!$admin->isAdmin()) {
            abort(403, 'Только администратор может начать чат');
        }
    
        // Единый тип чата
        $chat = Chat::firstOrCreate([
            'type' => 'admin_educator',
            'admin_id' => $admin->id,
            'participant_id' => $educator->id,
        ]);
    
        return redirect()->route('chats.show', $chat);
    }

    public function show(Chat $chat)
    {
       
        
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
        $user = auth()->user();
        if (!$user->isEducator()) {
            abort(403, 'Только воспитатели могут начинать чат с админом');
        }
    
        $admin = User::where('status', 'admin')->firstOrFail();
    
        // Единый тип чата
        $chat = Chat::firstOrCreate([
            'type' => 'admin_educator',
            'participant_id' => $user->id,
            'admin_id' => $admin->id,
        ]);
    
        return redirect()->route('chats.show', $chat);
    }

    public function startWithAdminEducator()
    {
        $user = auth()->user();
        if (!$user->isEducator()) {
            abort(403, 'Только воспитатели могут начинать чат с админом');
        }

        $admin = User::where('status', 'admin')->firstOrFail();

        // Ищем существующий чат или создаём новый
        $chat = Chat::firstOrCreate([
            'type' => 'educator_admin',
            'participant_id' => $user->id,
            'admin_id' => $admin->id,
        ]);

        return redirect()->route('chats.show', $chat);
    }

    public function getMessages(Chat $chat, Request $request)
    {


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
                // Родитель → чаты с воспитателями
                $chats = Chat::where('type', 'parent_educator')
                    ->where('parent_id', $user->id)
                    ->withCount(['messages as unread_count' => function($query) use ($user) {
                        $query->where('is_read', false)->where('sender_id', '!=', $user->id);
                    }])
                    ->with(['lastMessage', 'participant'])
                    ->get();
    
                // Чат с админом
                $adminChat = Chat::where('type', 'parent_admin')
                    ->where('parent_id', $user->id)
                    ->withCount(['messages as unread_count' => function($query) use ($user) {
                        $query->where('is_read', false)->where('sender_id', '!=', $user->id);
                    }])
                    ->with(['lastMessage'])
                    ->first();
            }
            elseif ($user->isEducator()) {
                // Воспитатель → чаты с родителями
                $chats = Chat::where('type', 'parent_educator')
                    ->where('participant_id', $user->id)
                    ->withCount(['messages as unread_count' => function($query) use ($user) {
                        $query->where('is_read', false)->where('sender_id', '!=', $user->id);
                    }])
                    ->with(['lastMessage', 'parent'])
                    ->get();
    
                // Чат с админом
                $adminChat = Chat::where('type', 'admin_educator')
                    ->where('participant_id', $user->id)
                    ->withCount(['messages as unread_count' => function($query) use ($user) {
                        $query->where('is_read', false)->where('sender_id', '!=', $user->id);
                    }])
                    ->with(['lastMessage'])
                    ->first();
            }
            elseif ($user->isAdmin()) {
                // Админ → чаты с родителями и воспитателями
                $chats = Chat::where('type', 'parent_admin')
                    ->orWhere(function($query) use ($user) {
                        $query->where('type', 'admin_educator')->where('admin_id', $user->id);
                    })
                    ->withCount(['messages as unread_count' => function($query) use ($user) {
                        $query->where('is_read', false)->where('sender_id', '!=', $user->id);
                    }])
                    ->with(['lastMessage', 'parent', 'participant'])
                    ->get();
            }
    
            // Подготавливаем данные для JSON
            $result = $chats->map(function($chat) {
                return [
                    'id' => $chat->id,
                    'unread_count' => $chat->unread_count,
                    'last_message_time' => optional($chat->lastMessage)->created_at,
                    'last_message_content' => optional($chat->lastMessage)->content,
                    'last_message_id' => optional($chat->lastMessage)->id,
                    'type' => $chat->type,
                    'participant_name' => match($chat->type) {
                        'parent_educator' => optional($chat->parent)->full_name ?? 'Не указан',
                        'parent_admin' => optional($chat->participant)->full_name ?? 'Администрация',
                        'admin_educator' => optional($chat->participant)->full_name ?? 'Администрация',
                        default => 'Пользователь'
                    },
                ];
            });
    
            $adminChatData = null;
            if ($adminChat) {
                $adminChatData = [
                    'id' => $adminChat->id,
                    'unread_count' => $adminChat->unread_count,
                    'last_message_time' => optional($adminChat->lastMessage)->created_at,
                    'last_message_content' => optional($adminChat->lastMessage)->content,
                    'last_message_id' => optional($adminChat->lastMessage)->id,
                    'type' => $adminChat->type,
                    'participant_name' => 'администрацией'
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