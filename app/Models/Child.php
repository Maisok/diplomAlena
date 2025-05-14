<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'patronymic',
        'birth_date',
        'group_id',
        'parent_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
}