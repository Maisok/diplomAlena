<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'parent_id', 'participant_id'];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function participant()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getLastMessageAttribute()
    {
        return $this->messages()->latest()->first();
    }

    public function unreadMessagesCount()
    {
        return $this->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', auth()->id())
            ->count();
    }

    // Новый метод для получения чата с администрацией для родителя
    public static function getAdminChatForParent($parentId)
    {
        return static::where('type', 'parent_admin')
            ->where('parent_id', $parentId)
            ->first();
    }

    // Новый метод для получения чатов воспитателя
    public static function getEducatorChats($educatorId)
    {
        return static::where('type', 'parent_educator')
            ->where('participant_id', $educatorId)
            ->get();
    }

    // Новый метод для получения всех админских чатов
    public static function getAllAdminChats()
    {
        return static::where('type', 'parent_admin')->get();
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}