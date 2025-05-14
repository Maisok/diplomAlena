<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration_minutes',
        'once_per_day',
        'unique_across_groups'
    ];

    public function scheduleItems()
    {
        return $this->hasMany(ScheduleItem::class);
    }
}