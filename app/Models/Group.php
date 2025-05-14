<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'educator_id',
    ];

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    public function children()
    {
        return $this->hasMany(Child::class);
    }

    public function scheduleItems()
    {
        return $this->hasMany(ScheduleItem::class);
    }
}