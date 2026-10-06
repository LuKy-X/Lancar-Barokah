<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionStatusHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductionService
{
    /**
     * The standard production stages in linear sequence.
     */
    public const STAGES = [
        'cutting' => 'Cutting (Pemotongan Pola)',
        'jahit' => 'Jahit (Penjahitan Bahan)',
        'printing' => 'Printing (Sablon / Cetak)',
        'packing' => 'Packing (Finishing & Kemas)',
        'selesai' => 'Selesai (Siap Diambil)',
    ];

    /**
     * Advance or update production status with history log.
     */
    public function updateStatus(Transaction $transaction, string $newStatus, ?string $catatan, User $user): Transaction
    {
        if (! array_key_exists($newStatus, self::STAGES)) {
            throw new InvalidArgumentException("Status produksi '{$newStatus}' tidak valid.");
        }

        return DB::transaction(function () use ($transaction, $newStatus, $catatan, $user) {
            $transaction->update([
                'status_produksi' => $newStatus,
            ]);

            TransactionStatusHistory::create([
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'status' => $newStatus,
                'waktu' => Carbon::now(),
                'catatan' => $catatan ?? ('Status produksi diperbarui menjadi '.self::STAGES[$newStatus]),
            ]);

            return $transaction;
        });
    }

    /**
     * Mark transaction as picked up by customer.
     * Enforces business rule: must be finished and paid in full.
     */
    public function markAsPickedUp(Transaction $transaction): Transaction
    {
        if ($transaction->status_produksi !== 'selesai') {
            throw new InvalidArgumentException('Barang belum selesai diproduksi. Tidak dapat diserahkan ke pelanggan.');
        }

        if ($transaction->status_pembayaran !== 'lunas') {
            throw new InvalidArgumentException('Pembayaran belum lunas. Selesaikan pelunasan terlebih dahulu sebelum penyerahan barang.');
        }

        $transaction->update([
            'status_pengambilan' => 'diambil',
        ]);

        return $transaction;
    }

    /**
     * Retrieve all overdue transactions (deadline passed and not finished).
     */
    public function getOverdueTransactions(): Collection
    {
        return Transaction::with(['customer', 'details'])
            ->where('status_produksi', '!=', 'selesai')
            ->whereDate('deadline', '<', Carbon::today())
            ->orderBy('deadline', 'asc')
            ->get();
    }
}
