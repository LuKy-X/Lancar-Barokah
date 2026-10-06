<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_metode',
        'nama_metode',
        'tipe',
        'nomor_rekening',
        'atas_nama',
        'keterangan',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include active payment methods.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Payments recorded using this payment method.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get human-readable label for payment channel type.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->tipe) {
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS (Digital)',
            'cash' => 'Tunai / Kasir',
            default => ucfirst((string) $this->tipe),
        };
    }

    /**
     * Format account display e.g. "8830192833 a/n CV Lancar Barokah".
     */
    public function getFormattedAccountAttribute(): string
    {
        if ($this->tipe === 'transfer' && $this->nomor_rekening) {
            return $this->nomor_rekening.($this->atas_nama ? ' a/n '.$this->atas_nama : '');
        }

        if ($this->tipe === 'qris') {
            return 'Scan QRIS'.($this->atas_nama ? ' ('.$this->atas_nama.')' : '');
        }

        return 'Pembayaran Kasir Workshop';
    }
}
