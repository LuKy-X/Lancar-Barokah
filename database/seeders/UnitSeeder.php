<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'kode_satuan' => 'PCS',
                'nama_satuan' => 'Pcs (Pieces / Buah)',
                'keterangan' => 'Satuan per potong pakaian atau kaos jadi',
                'is_active' => true,
            ],
            [
                'kode_satuan' => 'LSN',
                'nama_satuan' => 'Lusin (12 Pcs)',
                'keterangan' => 'Pemesanan grosir kelipatan 12 potong',
                'is_active' => true,
            ],
            [
                'kode_satuan' => 'TTK',
                'nama_satuan' => 'Titik / Sisi Sablon',
                'keterangan' => 'Satuan penempatan sablon/bordir (misal: Depan A3, Belakang A4)',
                'is_active' => true,
            ],
            [
                'kode_satuan' => 'ROL',
                'nama_satuan' => 'Roll / Gulung',
                'keterangan' => 'Satuan kain bahan atau rol pita label',
                'is_active' => true,
            ],
            [
                'kode_satuan' => 'PCK',
                'nama_satuan' => 'Pack / Kantong',
                'keterangan' => 'Satuan kemasan plastik atau packaging grosir',
                'is_active' => true,
            ],
            [
                'kode_satuan' => 'MTR',
                'nama_satuan' => 'Meter (m)',
                'keterangan' => 'Satuan panjang bahan kain atau cetak DTF meteran',
                'is_active' => true,
            ],
        ];

        foreach ($units as $u) {
            Unit::updateOrCreate(['kode_satuan' => $u['kode_satuan']], $u);
        }
    }
}
