<?php

namespace App\Exports;

use App\Models\Child;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ChildrenExport implements FromCollection, WithHeadings, WithMapping
{
    protected $group_id;

    public function __construct($group_id)
    {
        $this->group_id = $group_id;
    }

    public function collection()
    {
        return Child::with('group', 'parent')
            ->where('group_id', $this->group_id)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Фамилия',
            'Имя',
            'Отчество',
            'Дата рождения',
            'Группа',
            'Родитель',
        ];
    }

    public function map($child): array
    {
        return [
            $child->last_name,
            $child->first_name,
            $child->patronymic,
            $child->birth_date,
            $child->group->name,
            $child->parent->first_name . ' ' . $child->parent->last_name . ' ' . $child->parent->patronymic,
        ];
    }
}