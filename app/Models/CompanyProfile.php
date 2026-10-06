<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_usaha',
        'alamat',
        'telepon',
        'whatsapp',
        'email',
        'logo',
        'footer_invoice',
        'nama_bank',
        'no_rekening',
        'atas_nama',
    ];

    /**
     * Get the singleton CompanyProfile instance or create a default one.
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'nama_usaha' => 'Lancar Barokah Sablon & Konveksi',
            'alamat' => 'Jongkang Rt.04 Rw.05, Buran, Tasikmadu, Karanganyar',
            'telepon' => '085642233904',
            'whatsapp' => '085642233904',
            'email' => 'lancarbarokah.order@gmail.com',
            'footer_invoice' => 'Syarat & Ketentuan:\n1. Barang yang sudah selesai diproduksi wajib diambil maksimal 14 hari kerja.\n2. Pembayaran lunas sebelum barang diambil atau dikirim.',
            'nama_bank' => 'BCA',
            'no_rekening' => '7850760882',
            'atas_nama' => 'ALFIYAN NUR ROHMAN',
        ]);
    }
}
