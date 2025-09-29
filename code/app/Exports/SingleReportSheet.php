<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SingleReportSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    protected string $title;
    protected Collection $data;

    public function __construct(string $title, Collection $data)
    {
        $this->title = $title;

        $this->data = collect($data)->map(function ($row) {
            if (is_object($row) && method_exists($row, 'toArray')) {
                return $row->toArray();
            }
            return (array) $row;
        });
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return $this->data->first()
            ? array_keys((array) $this->data->first())
            : [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD'],
                ],
            ],
        ];
    }
}
