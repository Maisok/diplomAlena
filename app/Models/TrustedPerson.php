<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrustedPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'last_name',
        'first_name',
        'patronymic',
        'phone_number'
    ];

    public function parent()
    {
        return $this->belongsTo(\App\Models\User::class, 'parent_id');
    }
}