<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    public const ALL_STAGES = ['cutting', 'jahit', 'printing', 'packing', 'selesai'];

    public const STAGE_LABELS = [
        'cutting' => 'Cutting (Potong Bahan)',
        'jahit' => 'Jahit (Penjahitan Pola)',
        'printing' => 'Printing (Sablon / Cetak)',
        'packing' => 'Packing (Finishing & Kemas)',
        'selesai' => 'Selesai (Siap Diambil)',
    ];

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'user_id',
        'tanggal_transaksi',
        'deadline',
        'alamat_pengiriman',
        'biaya_pengiriman',
        'diskon',
        'refund',
        'subtotal',
        'total_tagihan',
        'status_produksi',
        'tahapan_produksi',
        'status_pembayaran',
        'status_pengambilan',
        'catatan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_transaksi' => 'date',
            'deadline' => 'date',
            'biaya_pengiriman' => 'decimal:2',
            'diskon' => 'decimal:2',
            'refund' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'total_tagihan' => 'decimal:2',
            'tahapan_produksi' => 'array',
        ];
    }

    /**
     * Get array of production stages configured for this transaction.
     * Fallback to all 5 standard stages if null or empty.
     *
     * @return array<string>
     */
    public function getTahapanListAttribute(): array
    {
        if (is_array($this->tahapan_produksi) && ! empty($this->tahapan_produksi)) {
            return array_values($this->tahapan_produksi);
        }

        return self::ALL_STAGES;
    }

    /**
     * Get the next stage in this transaction's specific pipeline.
     */
    public function getNextStage(): ?string
    {
        $stages = $this->tahapan_list;
        $currentIndex = array_search($this->status_produksi, $stages, true);

        if ($currentIndex !== false && isset($stages[$currentIndex + 1])) {
            return $stages[$currentIndex + 1];
        }

        return null;
    }

    /**
     * Get the previous stage in this transaction's specific pipeline.
     */
    public function getPreviousStage(): ?string
    {
        $stages = $this->tahapan_list;
        $currentIndex = array_search($this->status_produksi, $stages, true);

        if ($currentIndex !== false && $currentIndex > 0 && isset($stages[$currentIndex - 1])) {
            return $stages[$currentIndex - 1];
        }

        return null;
    }

    /**
     * Check if a specific stage exists in this transaction's pipeline.
     */
    public function hasStage(string $stage): bool
    {
        return in_array($stage, $this->tahapan_list, true);
    }

    /**
     * Friendly pipeline type label.
     */
    public function getPipelineLabelAttribute(): string
    {
        $stages = $this->tahapan_list;

        if ($stages === ['printing', 'packing', 'selesai']) {
            return 'Sablon Saja (Bawa Baju)';
        }

        if ($stages === self::ALL_STAGES) {
            return 'Produksi Lengkap (Dari Kain)';
        }

        if ($stages === ['jahit', 'printing', 'packing', 'selesai']) {
            return 'Jahit & Sablon';
        }

        return 'Alur Kustom ('.count($stages).' Tahap)';
    }

    /**
     * Customer associated with this transaction.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    /**
     * User (staff/admin) who created this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Line items in this order.
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Payments recorded for this transaction.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Production status transition histories.
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(TransactionStatusHistory::class)->orderBy('waktu', 'desc');
    }

    /**
     * Computed total amount paid across all payment records.
     */
    public function getTotalBayarAttribute(): float
    {
        return (float) $this->payments()->sum('jumlah_bayar');
    }

    /**
     * Computed remaining unpaid balance.
     */
    public function getSisaTagihanAttribute(): float
    {
        return max(0, (float) $this->total_tagihan - $this->total_bayar);
    }

    /**
     * Check if production deadline is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status_produksi === 'selesai') {
            return false;
        }

        return Carbon::parse($this->deadline)->isPast() && ! Carbon::parse($this->deadline)->isToday();
    }

    /**
     * Check if production deadline is approaching within 2 days.
     */
    public function getIsDeadlineNearAttribute(): bool
    {
        if ($this->status_produksi === 'selesai' || $this->is_overdue) {
            return false;
        }

        $deadline = Carbon::parse($this->deadline)->startOfDay();
        $today = Carbon::today();
        $diff = $today->diffInDays($deadline, false);

        return $diff >= 0 && $diff <= 2;
    }
}
