<?php

namespace App\Models;

use Database\Factories\TransactionDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    /** @use HasFactory<TransactionDetailFactory> */
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'nama_produk_snapshot',
        'ukuran',
        'jumlah',
        'harga_snapshot',
        'subtotal',
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
            'jumlah' => 'integer',
            'harga_snapshot' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    /**
     * The transaction this detail belongs to.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * The product item definition referenced.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
