<?php

namespace App\Exports;

use App\Models\Group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class GroupScheduleExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function collection()
    {
        return $this->group->scheduleItems()
            ->with('activityCategory')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Дата',
            'Время начала',
            'Время окончания',
            'Активность',
            'Продолжительность (мин)',
        ];
    }

    public function map($scheduleItem): array
    {
        return [
            $scheduleItem->date,
            $scheduleItem->start_time,
            $scheduleItem->end_time,
            $scheduleItem->activityCategory->name,
            $scheduleItem->activityCategory->duration_minutes,
        ];
    }

    public function title(): string
    {
        return 'Расписание группы ' . $this->group->name;
    }
}