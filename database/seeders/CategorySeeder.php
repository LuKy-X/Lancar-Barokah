<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'kode_kategori' => 'KAT-001',
                'nama_kategori' => 'Kaos & T-Shirt',
                'tipe' => 'produk',
                'deskripsi' => 'Kaos oblong, raglan, round-neck combed berbagai gramasi (30s, 24s, 20s)',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT-002',
                'nama_kategori' => 'Polo Shirt & Wangki',
                'tipe' => 'produk',
                'deskripsi' => 'Kaos kerah berkerah bahan lacoste pique, CVC, dan katun pe',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT-003',
                'nama_kategori' => 'Jaket, Hoodie & Zipper',
                'tipe' => 'produk',
                'deskripsi' => 'Jaket fleece katun, sweater crewneck, hoodie zipper, jaket coach',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT-004',
                'nama_kategori' => 'Kemeja & PDL / Korsa',
                'tipe' => 'produk',
                'deskripsi' => 'Kemeja dinas lapangan, seragam komunitas & organisasi bahan drill dan ripstop',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT-005',
                'nama_kategori' => 'Tote Bag & Merchandise',
                'tipe' => 'produk',
                'deskripsi' => 'Goodie bag, tas kanvas blacu, topi custom, dan merchandise sablon',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT-006',
                'nama_kategori' => 'Jasa Sablon Manual',
                'tipe' => 'jasa',
                'deskripsi' => 'Sablon manual screen printing dengan cat Plastisol curing, Rubber Matsui, dan Discharge',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT-007',
                'nama_kategori' => 'Jasa Sablon Digital & DTF',
                'tipe' => 'jasa',
                'deskripsi' => 'Cetak sablon transfer digital Direct to Film (DTF) full color presisi tinggi',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT-008',
                'nama_kategori' => 'Jasa Bordir Komputer',
                'tipe' => 'jasa',
                'deskripsi' => 'Bordir benang presisi tinggi logo dada, badge punggung, dan topi',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT-009',
                'nama_kategori' => 'Perlengkapan & Packaging',
                'tipe' => 'bahan',
                'deskripsi' => 'Plastik kemas OPP seal, hangtag tebal, label woven damask, dan silica gel',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['kode_kategori' => $cat['kode_kategori']], $cat);
        }
    }
}
