<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyProfile::updateOrCreate(
            ['id' => 1],
            [
                'nama_usaha' => 'Lancar Barokah Sablon & Konveksi',
                'alamat' => 'Jongkang Rt.04 Rw.05, Buran, Tasikmadu, Karanganyar',
                'telepon' => '085642233904',
                'whatsapp' => '085642233904',
                'email' => 'lancarbarokah.order@gmail.com',
                'footer_invoice' => 'Syarat & Ketentuan:\n1. Barang yang sudah selesai diproduksi wajib diambil maksimal 14 hari kerja.\n2. Pembayaran lunas sebelum barang diambil atau dikirim.\n3. Komplain maksimal 2x24 jam setelah barang diterima.',
                'nama_bank' => 'BCA',
                'no_rekening' => '7850760882',
                'atas_nama' => 'ALFIYAN NUR ROHMAN',
            ]
        );
    }
}
