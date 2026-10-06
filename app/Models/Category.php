<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'tipe',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Products belonging to this category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get human-readable label for category type.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->tipe) {
            'produk' => 'Pakaian / Produk Jadi',
            'jasa' => 'Jasa Cetak / Sablon / Jahit',
            'bahan' => 'Bahan Baku & Packaging',
            default => ucfirst((string) $this->tipe),
        };
    }

    /**
     * Get badge color styling classes for UI display.
     */
    public function getTypeBadgeClassesAttribute(): string
    {
        return match ($this->tipe) {
            'produk' => 'bg-emerald-50 text-emerald-800 border border-emerald-200/80',
            'jasa' => 'bg-teal-50 text-teal-800 border border-teal-200/80',
            'bahan' => 'bg-amber-50 text-amber-800 border border-amber-200/80',
            default => 'bg-slate-100 text-slate-700 border border-slate-200',
        };
    }
}
