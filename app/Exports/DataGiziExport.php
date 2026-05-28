<?php

namespace App\Exports;

use App\Models\DataGizi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataGiziExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $period;
    protected $status;
    protected $roleName;

    public function __construct($period, $status, $roleName)
    {
        $this->period = $period;
        $this->status = $status;
        $this->roleName = $roleName;
    }

    public function query()
    {
        return DataGizi::query()
            ->whereDate('period', $this->period)
            ->where('status', $this->status)
            ->whereHas('user.roles', function($q) {
                $q->where('name', $this->roleName);
            })
            // Opsional: Urutkan berdasarkan desa dan nama
            ->orderBy('desa_kelurahan', 'asc')
            ->orderBy('nama', 'asc');
    }

    public function headings(): array
    {
        return [
            'Desa/Kelurahan',
            'Nama Anak',
            'Berat (KG)',
            'Tinggi (CM)',
            'ZS TB/U',
            'ZS BB/U',
            'ZS BB/TB',
            'Status',
            'Tanggal Input'
        ];
    }

    public function map($row): array
    {
        return [
            $row->desa_kelurahan,
            $row->nama,
            $row->berat,
            $row->tinggi,
            $row->zs_tb_u,
            $row->zs_bb_u,
            $row->zs_bb_tb,
            $row->status,
            $row->created_at->format('Y-m-d H:i:s'),
        ];
    }
}