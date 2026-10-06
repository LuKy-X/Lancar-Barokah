<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_customer',
        'nama',
        'no_hp',
        'alamat',
        'email',
    ];

    /**
     * Generate the next auto-incremented customer code e.g. CUST-0001
     */
    public static function generateCustomerNumber(): string
    {
        $lastCustomer = static::withTrashed()
            ->where('no_customer', 'LIKE', 'CUST-%')
            ->orderBy('id', 'desc')
            ->first();

        if (! $lastCustomer) {
            return 'CUST-0001';
        }

        $number = (int) substr($lastCustomer->no_customer, 5);

        return sprintf('CUST-%04d', $number + 1);
    }

    /**
     * Transactions belonging to this customer.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
