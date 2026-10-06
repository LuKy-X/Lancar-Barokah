<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'unit_id',
        'kode_produk',
        'nama_produk',
        'satuan',
        'harga_default',
        'deskripsi',
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
            'harga_default' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * The category this product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    /**
     * The unit of measurement for this product.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

    /**
     * Transaction details referencing this product.
     */
    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Check if product requires multi-size breakdown (XS - 3XL).
     */
    public function isSized(): bool
    {
        return $this->category?->tipe === 'produk';
    }

    /**
     * Backward-compatible kategori accessor for legacy views/tests.
     */
    public function getKategoriAttribute(): string
    {
        $tipe = $this->category?->tipe;

        return match ($tipe) {
            'jasa' => 'service',
            'bahan' => 'material',
            default => 'product',
        };
    }
}
