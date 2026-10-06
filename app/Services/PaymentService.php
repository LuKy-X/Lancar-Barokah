<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PaymentService
{
    /**
     * Add a payment to a transaction and update payment status accordingly.
     */
    public function addPayment(Transaction $transaction, array $data, User $user): Payment
    {
        $amount = (float) ($data['jumlah_bayar'] ?? 0);

        if ($amount <= 0) {
            throw new InvalidArgumentException('Jumlah pembayaran harus lebih dari 0.');
        }

        $sisaTagihan = $transaction->sisa_tagihan;

        if ($amount > $sisaTagihan) {
            throw new InvalidArgumentException('Jumlah pembayaran melebihi sisa tagihan (Rp '.number_format($sisaTagihan, 0, ',', '.').').');
        }

        return DB::transaction(function () use ($transaction, $data, $user, $amount, $sisaTagihan) {
            // Determine automatic payment type if not explicitly provided
            $jenisBayar = $data['jenis_bayar'] ?? null;
            if (! $jenisBayar) {
                if ($amount >= $sisaTagihan) {
                    $jenisBayar = 'pelunasan';
                } elseif ($transaction->payments()->count() === 0) {
                    $jenisBayar = 'dp';
                } else {
                    $jenisBayar = 'cicilan';
                }
            }

            // Handle bukti_bayar upload or path
            $buktiBayarPath = null;
            if (isset($data['bukti_bayar'])) {
                if ($data['bukti_bayar'] instanceof UploadedFile) {
                    $buktiBayarPath = $data['bukti_bayar']->store('payments', 'public');
                } elseif (is_string($data['bukti_bayar'])) {
                    $buktiBayarPath = $data['bukti_bayar'];
                }
            }

            $payment = Payment::create([
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'payment_method_id' => $data['payment_method_id'] ?? null,
                'tanggal_bayar' => $data['tanggal_bayar'] ?? Carbon::now()->toDateString(),
                'jenis_bayar' => $jenisBayar,
                'metode_bayar' => $data['metode_bayar'] ?? 'cash',
                'jumlah_bayar' => $amount,
                'bukti_bayar' => $buktiBayarPath,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // Recalculate transaction status
            app(TransactionService::class)->recalculateTotals($transaction);

            return $payment;
        });
    }
}
