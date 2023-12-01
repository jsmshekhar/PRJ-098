<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CustomerExport implements FromCollection, WithColumnFormatting, WithColumnWidths, ShouldAutoSize, WithHeadings, WithEvents
{
    use Exportable;
    protected $data, $sheet;

    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->data);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 30
        ];
    }

    public function headings(): array
    {
        return [
            'Sr. No',
            'Customer Id',
            'Name',
            'Email',
            'Phone',
            'Joining Date',
            'Subscription Validity',
            "Verification Status",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(10);


                $event->sheet->getDelegate()->getStyle('1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
