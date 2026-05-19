<?php

namespace Modules\Project\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WbsTemplateExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    public function headings(): array
    {
        return [
            'JENIS DOKUMEN',
            'DISPLIN',
            'ITEM CODE',
            'DESCRIPTIONS',
            'FASE',
            'PIC',
            'NAMA FILE',
            'TANGGAL MULAI RENCANA',
            'TANGGAL SELESAI RENCANA',
        ];
    }

    public function array(): array
    {
        return [
            [
                'DRAWING',
                'ARCHITECTURE',
                'AR-001',
                'FLOOR PLAN',
                'PHASE 1',
                'JOHN DOE',
                'AR-001_FLOOR_PLAN',
                '2026-05-01',
                '2026-05-30',
            ],
            [
                'DOCUMENT',
                'STRUCTURE',
                'ST-001',
                'COLUMN DETAILS',
                'PHASE 1',
                'JANE DOE',
                'ST-001_COLUMN_DETAILS',
                '2026-05-05',
                '2026-05-25',
            ]
        ];
    }

    public function title(): string
    {
        return 'TIDP';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF174D9D']]],
        ];
    }
}
