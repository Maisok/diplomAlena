<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'user_id',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'answered_at' => 'datetime',
    ];

    // Для внутреннего использования, не отображается в публичных данных
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Для внутреннего использования, не отображается в публичных данных
    public function admin()
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUnanswered($query)
    {
        return $query->whereNull('answer');
    }
}