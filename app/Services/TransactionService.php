<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransactionStatusHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TransactionService
{
    /**
     * Generate the next unique invoice number with DB locking: INV-YYYYMMDD-NNN
     */
    public function generateInvoiceNumber(): string
    {
        $datePrefix = 'INV-'.Carbon::now()->format('Ymd').'-';

        $lastTransaction = Transaction::where('invoice_no', 'LIKE', $datePrefix.'%')
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        if (! $lastTransaction) {
            return $datePrefix.'001';
        }

        $lastSeq = (int) substr($lastTransaction->invoice_no, -3);
        $nextSeq = str_pad((string) ($lastSeq + 1), 3, '0', STR_PAD_LEFT);

        return $datePrefix.$nextSeq;
    }

    /**
     * Create a complete transaction with items (supporting multi-size breakdown),
     * optional initial payment, and initial status history.
     */
    public function createTransaction(array $data, User $user): Transaction
    {
        if (empty($data['items'])) {
            throw new InvalidArgumentException('Transaksi harus memiliki minimal satu item produk/jasa.');
        }

        return DB::transaction(function () use ($data, $user) {
            $invoiceNo = $this->generateInvoiceNumber();

            // Calculate item subtotals server-side and expand multi-size items
            $subtotal = 0;
            $itemsProcessed = [];

            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                $snapshotName = ! empty($item['nama_produk'])
                    ? trim($item['nama_produk'])
                    : ($product ? $product->nama_produk : 'Produk/Jasa');

                $price = (float) ($item['harga'] ?? ($product ? $product->harga_default : 0));
                if ($price < 0) {
                    $price = 0;
                }

                // Check if this item has a multi-size breakdown (e.g. ['S' => 5, 'M' => 10, ...])
                if (! empty($item['sizes']) && is_array($item['sizes'])) {
                    $hasAnySize = false;
                    foreach ($item['sizes'] as $sizeKey => $sizeQty) {
                        $qty = (int) $sizeQty;
                        if ($qty > 0) {
                            $hasAnySize = true;
                            $itemSubtotal = $qty * $price;
                            $subtotal += $itemSubtotal;

                            $itemsProcessed[] = [
                                'product_id' => $item['product_id'],
                                'nama_produk_snapshot' => $snapshotName,
                                'ukuran' => (string) $sizeKey,
                                'jumlah' => $qty,
                                'harga_snapshot' => $price,
                                'subtotal' => $itemSubtotal,
                                'catatan' => $item['catatan'] ?? null,
                            ];
                        }
                    }

                    // Fallback if sizes array was passed but all quantities were 0
                    if (! $hasAnySize && isset($item['jumlah']) && (int) $item['jumlah'] > 0) {
                        $qty = (int) $item['jumlah'];
                        $itemSubtotal = $qty * $price;
                        $subtotal += $itemSubtotal;

                        $itemsProcessed[] = [
                            'product_id' => $item['product_id'],
                            'nama_produk_snapshot' => $snapshotName,
                            'ukuran' => $item['ukuran'] ?? null,
                            'jumlah' => $qty,
                            'harga_snapshot' => $price,
                            'subtotal' => $itemSubtotal,
                            'catatan' => $item['catatan'] ?? null,
                        ];
                    }
                } else {
                    // Non-sized product or simple single-quantity item
                    $qty = (int) ($item['jumlah'] ?? 1);
                    if ($qty <= 0) {
                        $qty = 1;
                    }

                    $itemSubtotal = $qty * $price;
                    $subtotal += $itemSubtotal;

                    $itemsProcessed[] = [
                        'product_id' => $item['product_id'],
                        'nama_produk_snapshot' => $snapshotName,
                        'ukuran' => $item['ukuran'] ?? null,
                        'jumlah' => $qty,
                        'harga_snapshot' => $price,
                        'subtotal' => $itemSubtotal,
                        'catatan' => $item['catatan'] ?? null,
                    ];
                }
            }

            if (empty($itemsProcessed)) {
                throw new InvalidArgumentException('Total kuantitas item pesanan harus lebih dari 0.');
            }

            $biayaPengiriman = max(0, (float) ($data['biaya_pengiriman'] ?? 0));
            $diskon = max(0, (float) ($data['diskon'] ?? 0));
            $totalTagihan = max(0, $subtotal + $biayaPengiriman - $diskon);

            $initialPayment = (float) ($data['initial_payment'] ?? 0);
            if ($initialPayment > $totalTagihan) {
                throw new InvalidArgumentException('Pembayaran awal (Rp '.number_format($initialPayment, 0, ',', '.').') tidak boleh melebihi total tagihan (Rp '.number_format($totalTagihan, 0, ',', '.').').');
            }

            $statusPembayaran = 'belum_bayar';
            if ($initialPayment >= $totalTagihan && $totalTagihan > 0) {
                $statusPembayaran = 'lunas';
            } elseif ($initialPayment > 0) {
                $statusPembayaran = 'dp';
            }

            // Determine custom production stages or fallback to all standard stages
            $tahapanProduksi = ! empty($data['tahapan_produksi']) && is_array($data['tahapan_produksi'])
                ? array_values(array_intersect(Transaction::ALL_STAGES, $data['tahapan_produksi']))
                : Transaction::ALL_STAGES;

            if (empty($tahapanProduksi)) {
                $tahapanProduksi = Transaction::ALL_STAGES;
            }

            $initialStage = $tahapanProduksi[0];

            $transaction = Transaction::create([
                'invoice_no' => $invoiceNo,
                'customer_id' => $data['customer_id'],
                'user_id' => $user->id,
                'tanggal_transaksi' => $data['tanggal_transaksi'] ?? Carbon::now()->toDateString(),
                'deadline' => $data['deadline'],
                'alamat_pengiriman' => $data['alamat_pengiriman'] ?? null,
                'biaya_pengiriman' => $biayaPengiriman,
                'diskon' => $diskon,
                'refund' => 0,
                'subtotal' => $subtotal,
                'total_tagihan' => $totalTagihan,
                'status_produksi' => $initialStage,
                'tahapan_produksi' => $tahapanProduksi,
                'status_pembayaran' => $statusPembayaran,
                'status_pengambilan' => 'belum_diambil',
                'catatan' => $data['catatan'] ?? null,
            ]);

            foreach ($itemsProcessed as $itemData) {
                $itemData['transaction_id'] = $transaction->id;
                TransactionDetail::create($itemData);
            }

            // Initial production status history based on the first configured stage
            $stageLabel = Transaction::STAGE_LABELS[$initialStage] ?? ucfirst($initialStage);
            TransactionStatusHistory::create([
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'status' => $initialStage,
                'waktu' => Carbon::now(),
                'catatan' => "Pesanan baru dibuat, memulai tahap {$stageLabel}.",
            ]);

            // Initial payment recording if provided
            if ($initialPayment > 0) {
                $buktiBayarPath = null;
                if (isset($data['bukti_bayar'])) {
                    if ($data['bukti_bayar'] instanceof UploadedFile) {
                        $buktiBayarPath = $data['bukti_bayar']->store('payments', 'public');
                    } elseif (is_string($data['bukti_bayar'])) {
                        $buktiBayarPath = $data['bukti_bayar'];
                    }
                }

                Payment::create([
                    'transaction_id' => $transaction->id,
                    'user_id' => $user->id,
                    'payment_method_id' => $data['payment_method_id'] ?? null,
                    'tanggal_bayar' => $data['tanggal_transaksi'] ?? Carbon::now()->toDateString(),
                    'jenis_bayar' => $initialPayment >= $totalTagihan ? 'pelunasan' : 'dp',
                    'metode_bayar' => $data['metode_bayar'] ?? 'cash',
                    'jumlah_bayar' => $initialPayment,
                    'bukti_bayar' => $buktiBayarPath,
                    'keterangan' => 'Pembayaran awal '.($initialPayment >= $totalTagihan ? 'Pelunasan' : 'Uang Muka (DP)'),
                ]);
            }

            return $transaction;
        });
    }

    /**
     * Recalculate and update transaction totals and payment status from actual records.
     */
    public function recalculateTotals(Transaction $transaction): void
    {
        $subtotal = (float) $transaction->details()->sum('subtotal');
        $totalTagihan = max(0, $subtotal + (float) $transaction->biaya_pengiriman - (float) $transaction->diskon - (float) $transaction->refund);
        $totalBayar = (float) $transaction->payments()->sum('jumlah_bayar');

        $statusPembayaran = 'belum_bayar';
        if ($totalBayar >= $totalTagihan && $totalTagihan > 0) {
            $statusPembayaran = 'lunas';
        } elseif ($totalBayar > 0) {
            $statusPembayaran = $transaction->payments()->count() > 1 ? 'cicilan' : 'dp';
        }

        $transaction->update([
            'subtotal' => $subtotal,
            'total_tagihan' => $totalTagihan,
            'status_pembayaran' => $statusPembayaran,
        ]);
    }
}
