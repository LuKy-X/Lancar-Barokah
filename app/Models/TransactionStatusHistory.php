<?php

namespace App\Models;

use Database\Factories\TransactionStatusHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionStatusHistory extends Model
{
    /** @use HasFactory<TransactionStatusHistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'status',
        'waktu',
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
            'waktu' => 'datetime',
        ];
    }

    /**
     * The transaction this history event belongs to.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * The user who logged this status update.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
