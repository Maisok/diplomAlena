<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'activity_category_id',
        'date',
        'start_time',
        'end_time'
    ];

    protected $dates = ['date'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function activityCategory()
    {
        return $this->belongsTo(ActivityCategory::class);
    }
}