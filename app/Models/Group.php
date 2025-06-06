<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function educators()
    {
        return $this->belongsToMany(User::class, 'groups_educator', 'group_id', 'educator_id')->withTimestamps();
    }

    public function children()
    {
        return $this->hasMany(Child::class);
    }

    public function scheduleItems()
    {
        return $this->hasMany(ScheduleItem::class);
    }

    public function getFormattedScheduleAttribute()
    {
        return $this->scheduleItems->groupBy(function ($item) {
            $date = is_string($item->date) ? \Carbon\Carbon::parse($item->date) : $item->date;
            return $date->dayOfWeekIso;
        });
    }
}