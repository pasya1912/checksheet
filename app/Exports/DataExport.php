<?php

namespace App\Exports;

use App\Models\Checkdata;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataExport  implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $query;
    function __construct($query)
    {
        $this->query = $query;

    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Product',
            'Line',
            'Model',
            'Checksheet',
            'Area',
            'Cell',
            'First/Last',
            'Shift',
            'Tanggal',
            'Jam',
            'Minimum',
            'Maximum',
            'Value',
            'Status',
            'Revisi Value',
            'Note',
            'Checker',
            'JP Approver',
            'Leader Approver',
            'Supervisor Approver',
            'Manager Approver'




        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Define your styles here
        $sheet->getStyle('A1:R1')->getFont()->setBold(true);
    }
}
