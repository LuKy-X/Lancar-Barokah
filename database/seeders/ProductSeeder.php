<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catMap = Category::pluck('id', 'kode_kategori');
        $unitMap = Unit::pluck('id', 'kode_satuan');

        $products = [
            [
                'kode_produk' => 'PRD-001',
                'nama_produk' => 'Kaos Polos Cotton Combed 30s',
                'category_id' => $catMap['KAT-001'] ?? 1,
                'unit_id' => $unitMap['PCS'] ?? 1,
                'satuan' => 'pcs',
                'harga_default' => 45000,
                'deskripsi' => 'Bahan adem, lembut, standar distro combed 30s',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'PRD-002',
                'nama_produk' => 'Kaos Polos Cotton Combed 24s Tebal',
                'category_id' => $catMap['KAT-001'] ?? 1,
                'unit_id' => $unitMap['PCS'] ?? 1,
                'satuan' => 'pcs',
                'harga_default' => 52000,
                'deskripsi' => 'Bahan tebal nyaman combed 24s, cocok untuk sablon plastisol tebal',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'PRD-003',
                'nama_produk' => 'Kaos Polo Shirt Lacoste Pique CVC',
                'category_id' => $catMap['KAT-002'] ?? 2,
                'unit_id' => $unitMap['PCS'] ?? 1,
                'satuan' => 'pcs',
                'harga_default' => 68000,
                'deskripsi' => 'Kaos berkerah formal lacoste pique cvc premium',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'PRD-004',
                'nama_produk' => 'Hoodie Jumper Cotton Fleece',
                'category_id' => $catMap['KAT-003'] ?? 3,
                'unit_id' => $unitMap['PCS'] ?? 1,
                'satuan' => 'pcs',
                'harga_default' => 125000,
                'deskripsi' => 'Hoodie katun fleece hangat tebal dengan kantong kanguru',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'PRD-005',
                'nama_produk' => 'Tote Bag Kanvas Premium 30x40',
                'category_id' => $catMap['KAT-005'] ?? 5,
                'unit_id' => $unitMap['PCS'] ?? 1,
                'satuan' => 'pcs',
                'harga_default' => 25000,
                'deskripsi' => 'Tas kanvas tebal dengan tali webbing kuat',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'PRD-006',
                'nama_produk' => 'Kemeja PDL / Korsa American Drill',
                'category_id' => $catMap['KAT-004'] ?? 4,
                'unit_id' => $unitMap['PCS'] ?? 1,
                'satuan' => 'pcs',
                'harga_default' => 110000,
                'deskripsi' => 'Kemeja dinas PDL / seragam lapangan American drill 1919',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'SRV-001',
                'nama_produk' => 'Jasa Sablon Plastisol 2 Warna A3',
                'category_id' => $catMap['KAT-006'] ?? 6,
                'unit_id' => $unitMap['TTK'] ?? 3,
                'satuan' => 'titik',
                'harga_default' => 15000,
                'deskripsi' => 'Sablon manual tinta plastisol curing kilap / doff',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'SRV-002',
                'nama_produk' => 'Jasa Sablon Rubber Matsui 1 Warna A4',
                'category_id' => $catMap['KAT-006'] ?? 6,
                'unit_id' => $unitMap['TTK'] ?? 3,
                'satuan' => 'titik',
                'harga_default' => 8000,
                'deskripsi' => 'Sablon manual waterbased rubber matsui lembut di tangan',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'SRV-003',
                'nama_produk' => 'Jasa Sablon DTF Premium Full Color A3',
                'category_id' => $catMap['KAT-007'] ?? 7,
                'unit_id' => $unitMap['TTK'] ?? 3,
                'satuan' => 'titik',
                'harga_default' => 25000,
                'deskripsi' => 'Cetak digital direct to film full color gradasi tanpa batasan warna',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'SRV-004',
                'nama_produk' => 'Bordir Komputer Logo Dada',
                'category_id' => $catMap['KAT-008'] ?? 8,
                'unit_id' => $unitMap['TTK'] ?? 3,
                'satuan' => 'titik',
                'harga_default' => 12000,
                'deskripsi' => 'Bordir komputer presisi logo dada ukuran maks 8x8 cm',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'MAT-001',
                'nama_produk' => 'Plastik Packing OPP Sablon Exclusive',
                'category_id' => $catMap['KAT-009'] ?? 9,
                'unit_id' => $unitMap['PCK'] ?? 5,
                'satuan' => 'pack',
                'harga_default' => 1500,
                'deskripsi' => 'Plastik pembungkus rapi berperekat seal tebal',
                'is_active' => true,
            ],
            [
                'kode_produk' => 'MAT-002',
                'nama_produk' => 'Label Woven Damask Custom (per 100 pcs)',
                'category_id' => $catMap['KAT-009'] ?? 9,
                'unit_id' => $unitMap['ROL'] ?? 4,
                'satuan' => 'roll',
                'harga_default' => 75000,
                'deskripsi' => 'Label tenun woven damask halus untuk kerah pakaian',
                'is_active' => true,
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['kode_produk' => $prod['kode_produk']], $prod);
        }
    }
}
