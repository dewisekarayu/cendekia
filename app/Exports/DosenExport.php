<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DosenExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected string $search;
    protected ?string $prodiFilter;
    protected int $no = 0;

    public function __construct(string $search = '', ?string $prodiFilter = null)
    {
        $this->search      = $search;
        $this->prodiFilter = $prodiFilter;
    }

    public function query(): Builder
    {
        $query = User::role('dosen')->with('programStudi');

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('nip_nim', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->prodiFilter) {
            $query->where('program_studi_id', $this->prodiFilter);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'No.',
            'NIDN/NIP',
            'Nama Lengkap',
            'Email',
            'No. Telepon',
            'Program Studi',
            'Status',
            'Tanggal Daftar',
        ];
    }

    public function map($row): array
    {
        $statusMap = [
            'aktif'     => 'Aktif',
            'non_aktif' => 'Non-Aktif',
        ];

        $this->no++;

        return [
            $this->no,
            $row->nip_nim ?? '-',
            $row->name,
            $row->email,
            $row->telepon ?? '-',
            $row->programStudi?->nama_prodi ?? '-',
            $statusMap[$row->status] ?? ucfirst($row->status ?? '-'),
            $row->created_at?->format('d/m/Y') ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF321270'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->freezePane('A2');

        return [];
    }

    public function title(): string
    {
        return 'Data Dosen';
    }
}
