<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view any products.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the product.
     */
    public function view(User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create products.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the product.
     * Admin only, and cannot delete if used in transaction details.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->isAdmin() && $product->transactionDetails()->count() === 0;
    }
}
