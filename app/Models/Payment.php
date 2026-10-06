<?php

namespace App\Models;

use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'payment_method_id',
        'tanggal_bayar',
        'jenis_bayar',
        'metode_bayar',
        'jumlah_bayar',
        'bukti_bayar',
        'keterangan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'jumlah_bayar' => 'decimal:2',
        ];
    }

    /**
     * Get the public URL of the payment proof.
     */
    public function getBuktiBayarUrlAttribute(): ?string
    {
        if (! $this->bukti_bayar) {
            return null;
        }

        return Storage::url($this->bukti_bayar);
    }

    /**
     * Check if the payment proof is a PDF file.
     */
    public function isBuktiBayarPdf(): bool
    {
        if (! $this->bukti_bayar) {
            return false;
        }

        return str_ends_with(strtolower($this->bukti_bayar), '.pdf');
    }

    /**
     * The transaction this payment belongs to.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * The user who recorded this payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * The payment method/account used.
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class)->withTrashed();
    }
}
