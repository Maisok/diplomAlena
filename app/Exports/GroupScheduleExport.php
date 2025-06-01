<?php

namespace App\Exports;

use App\Models\Group;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GroupScheduleExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function array(): array
    {
        // Подготавливаем дни недели
        $days = [
            1 => 'Понедельник',
            2 => 'Вторник',
            3 => 'Среда',
            4 => 'Четверг',
            5 => 'Пятница'
        ];

        // Подготавливаем временные слоты
        $timeSlots = [];
        for ($hour = 7; $hour <= 18; $hour++) {
            $startTime = sprintf('%02d:00', $hour);
            $endTime = sprintf('%02d:00', $hour + 1);
            $timeSlots[$startTime] = "$startTime – $endTime";
        }

        // Загружаем расписание
        $scheduleItems = $this->group->scheduleItems()
            ->with('activityCategory')
            ->get()
            ->groupBy(fn($item) => \Carbon\Carbon::parse($item->date)->dayOfWeekIso);

        // Формируем данные
        $data = [];

        foreach ($timeSlots as $timeLabel => $fullTime) {
            $row = [$timeLabel];

            foreach ([1, 2, 3, 4, 5] as $dayNum) {
                $activities = collect($scheduleItems->get($dayNum, []))
                    ->filter(fn($item) => date('H:i', strtotime($item->start_time)) == $timeLabel);

                if ($activities->isNotEmpty()) {
                    $text = '';
                    foreach ($activities as $act) {
                        $text .= "{$act->activityCategory->name}\n";
                    }
                    $row[] = trim($text);
                } else {
                    $row[] = '';
                }
            }

            $data[] = $row;
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Время', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница'];
    }

    public function title(): string
    {
        return 'Расписание';
    }

    /**
     * Автоматическое оформление стилей (перенос текста, авто-высота)
     */
    public function styles(Worksheet $sheet)
    {
        // Получаем количество столбцов
        $colCount = count($this->headings());
        $highestColumn = Coordinate::stringFromColumnIndex($colCount);
        $lastRow = count($this->array()) + 1; // +1 для заголовков
    
        // Отключаем перенос текста
        $sheet->getStyle("A1:{$highestColumn}{$lastRow}")
            ->getAlignment()
            ->setWrapText(false);
    
        // Автоширина для всех колонок
        for ($col = 1; $col <= $colCount; $col++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }
    
        return [];
    }
}