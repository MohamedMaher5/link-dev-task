<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SingleReportSheet implements FromCollection, WithTitle, WithHeadings
{
    protected string $title;
    protected Collection $data;

    public function __construct(string $title, $data)
    {
        $this->title = $title;
        $this->data = collect($data);
    }

    public function collection()
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
}
