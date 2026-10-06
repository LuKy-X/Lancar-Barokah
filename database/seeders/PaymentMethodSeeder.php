<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'kode_metode' => 'PAY-BCA',
                'nama_metode' => 'Bank Central Asia (BCA)',
                'tipe' => 'transfer',
                'nomor_rekening' => '8830192833',
                'atas_nama' => 'CV LANCAR BAROKAH',
                'keterangan' => 'KCP Sleman Yogyakarta. Harap sertakan nomor invoice pada berita transfer.',
                'is_active' => true,
            ],
            [
                'kode_metode' => 'PAY-MDR',
                'nama_metode' => 'Bank Mandiri',
                'tipe' => 'transfer',
                'nomor_rekening' => '1370019283344',
                'atas_nama' => 'CV LANCAR BAROKAH',
                'keterangan' => 'KC Sudirman Yogyakarta. Konfirmasi via WhatsApp setelah transfer berhasil.',
                'is_active' => true,
            ],
            [
                'kode_metode' => 'PAY-QRS',
                'nama_metode' => 'QRIS Lancar Barokah',
                'tipe' => 'qris',
                'nomor_rekening' => 'ID1020304050601',
                'atas_nama' => 'CV LANCAR BAROKAH',
                'keterangan' => 'Scan langsung menggunakan m-Banking (BCA, Mandiri, BRI, BNI) atau E-Wallet (GoPay, OVO, Dana, ShopeePay).',
                'is_active' => true,
            ],
            [
                'kode_metode' => 'PAY-CSH',
                'nama_metode' => 'Kasir Tunai Workshop',
                'tipe' => 'cash',
                'nomor_rekening' => null,
                'atas_nama' => 'KASIR WORKSHOP',
                'keterangan' => 'Pembayaran tunai langsung di meja kasir workshop Lancar Barokah.',
                'is_active' => true,
            ],
        ];

        foreach ($methods as $m) {
            PaymentMethod::updateOrCreate(['kode_metode' => $m['kode_metode']], $m);
        }
    }
}
