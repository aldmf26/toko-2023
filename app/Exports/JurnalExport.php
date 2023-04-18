<?php

namespace App\Exports;

use App\Models\Jurnal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class JurnalExport  implements FromView, WithEvents
{
    protected $tgl1;
    protected $tgl2;
    protected $id_proyek;
    protected $totalrow;

    public function __construct($tgl1, $tgl2, $id_proyek, $totalrow)
    {
        $this->tgl1 = $tgl1;
        $this->tgl2 = $tgl2;
        $this->id_proyek = $id_proyek;
        $this->totalrow = $totalrow;
    }

    public function view(): View
    {
        if ($this->id_proyek == 0) {
            $jurnal = Jurnal::whereBetween('tgl', [$this->tgl1, $this->tgl2])->orderBY('id_jurnal', 'DESC')->get();
        } else {
            $jurnal = Jurnal::whereBetween('tgl', [$this->tgl1, $this->tgl2])->where('id_proyek', $this->id_proyek)->orderBY('id_jurnal', 'DESC')->get();
        }
        return view('exports.jurnal', [
            'jurnal' => $jurnal
        ]);
    }



    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $totalrow = $this->totalrow + 1;
                $cellRange = 'A1:K1';
                // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->setAutoFilter($cellRange);
                $event->sheet->getStyle('A1:K1')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'font' => [
                        'name'  =>  'Calibri',
                        'size'  =>  10,
                        'bold' => true
                    ]
                ]);
                $event->sheet->getStyle('A2:K' . $totalrow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'font' => [
                        'name'  =>  'Calibri',
                        'size'  =>  10,
                        'bold' => false
                    ]
                ]);
            },
        ];
    }
}