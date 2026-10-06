<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionStatusHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $petugas = User::where('role', 'petugas')->first() ?? $admin;

        $customers = Customer::all();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $orders = [
            [
                'invoice_no' => 'INV-20260901-001',
                'customer_idx' => 0,
                'user' => $admin,
                'tanggal' => Carbon::now()->subDays(14)->toDateString(),
                'deadline' => Carbon::now()->subDays(2)->toDateString(),
                'status_produksi' => 'selesai',
                'status_pembayaran' => 'lunas',
                'status_pengambilan' => 'diambil',
                'items' => [
                    ['kode' => 'PRD-001', 'ukuran' => 'L', 'qty' => 50, 'harga' => 45000],
                    ['kode' => 'SRV-001', 'ukuran' => null, 'qty' => 50, 'harga' => 15000],
                ],
                'payments' => [
                    ['jenis' => 'dp', 'metode' => 'transfer', 'amount' => 1500000, 'date' => Carbon::now()->subDays(14)],
                    ['jenis' => 'pelunasan', 'metode' => 'cash', 'amount' => 1500000, 'date' => Carbon::now()->subDays(2)],
                ],
                'history' => ['cutting', 'jahit', 'printing', 'packing', 'selesai'],
            ],
            [
                'invoice_no' => 'INV-20260905-002',
                'customer_idx' => 1,
                'user' => $petugas,
                'tanggal' => Carbon::now()->subDays(10)->toDateString(),
                'deadline' => Carbon::now()->subDays(1)->toDateString(), // Overdue!
                'status_produksi' => 'printing',
                'status_pembayaran' => 'dp',
                'status_pengambilan' => 'belum_diambil',
                'items' => [
                    ['kode' => 'PRD-002', 'ukuran' => 'XL', 'qty' => 35, 'harga' => 52000],
                    ['kode' => 'SRV-003', 'ukuran' => null, 'qty' => 35, 'harga' => 25000],
                ],
                'payments' => [
                    ['jenis' => 'dp', 'metode' => 'qris', 'amount' => 1500000, 'date' => Carbon::now()->subDays(10)],
                ],
                'history' => ['cutting', 'jahit', 'printing'],
            ],
            [
                'invoice_no' => 'INV-20260910-003',
                'customer_idx' => 2,
                'user' => $admin,
                'tanggal' => Carbon::now()->subDays(5)->toDateString(),
                'deadline' => Carbon::now()->addDays(1)->toDateString(), // Deadline near!
                'status_produksi' => 'packing',
                'status_pembayaran' => 'cicilan',
                'status_pengambilan' => 'belum_diambil',
                'items' => [
                    ['kode' => 'PRD-003', 'ukuran' => 'M', 'qty' => 40, 'harga' => 68000],
                    ['kode' => 'SRV-004', 'ukuran' => null, 'qty' => 40, 'harga' => 12000],
                ],
                'payments' => [
                    ['jenis' => 'dp', 'metode' => 'transfer', 'amount' => 1600000, 'date' => Carbon::now()->subDays(5)],
                    ['jenis' => 'cicilan', 'metode' => 'transfer', 'amount' => 800000, 'date' => Carbon::now()->subDays(2)],
                ],
                'history' => ['cutting', 'jahit', 'printing', 'packing'],
            ],
            [
                'invoice_no' => 'INV-20260912-004',
                'customer_idx' => 3,
                'user' => $petugas,
                'tanggal' => Carbon::now()->subDays(3)->toDateString(),
                'deadline' => Carbon::now()->addDays(4)->toDateString(),
                'status_produksi' => 'jahit',
                'status_pembayaran' => 'dp',
                'status_pengambilan' => 'belum_diambil',
                'items' => [
                    ['kode' => 'PRD-004', 'ukuran' => 'L', 'qty' => 25, 'harga' => 125000],
                    ['kode' => 'SRV-003', 'ukuran' => null, 'qty' => 25, 'harga' => 25000],
                ],
                'payments' => [
                    ['jenis' => 'dp', 'metode' => 'transfer', 'amount' => 2000000, 'date' => Carbon::now()->subDays(3)],
                ],
                'history' => ['cutting', 'jahit'],
            ],
            [
                'invoice_no' => 'INV-20260914-005',
                'customer_idx' => 4,
                'user' => $admin,
                'tanggal' => Carbon::now()->subDays(1)->toDateString(),
                'deadline' => Carbon::now()->addDays(7)->toDateString(),
                'status_produksi' => 'cutting',
                'status_pembayaran' => 'belum_bayar',
                'status_pengambilan' => 'belum_diambil',
                'items' => [
                    ['kode' => 'PRD-005', 'ukuran' => 'Standard', 'qty' => 100, 'harga' => 25000],
                    ['kode' => 'SRV-002', 'ukuran' => null, 'qty' => 100, 'harga' => 8000],
                ],
                'payments' => [],
                'history' => ['cutting'],
            ],
            [
                'invoice_no' => 'INV-20260915-006',
                'customer_idx' => 5,
                'user' => $petugas,
                'tanggal' => Carbon::now()->toDateString(),
                'deadline' => Carbon::now()->addDays(5)->toDateString(),
                'status_produksi' => 'cutting',
                'status_pembayaran' => 'lunas',
                'status_pengambilan' => 'belum_diambil',
                'items' => [
                    ['kode' => 'PRD-001', 'ukuran' => 'M', 'qty' => 30, 'harga' => 45000],
                    ['kode' => 'SRV-001', 'ukuran' => null, 'qty' => 30, 'harga' => 15000],
                ],
                'payments' => [
                    ['jenis' => 'pelunasan', 'metode' => 'cash', 'amount' => 1800000, 'date' => Carbon::now()],
                ],
                'history' => ['cutting'],
            ],
        ];

        foreach ($orders as $orderData) {
            $customer = $customers[$orderData['customer_idx'] % $customers->count()];

            $subtotal = 0;
            $itemsToInsert = [];
            foreach ($orderData['items'] as $item) {
                $product = $products->firstWhere('kode_produk', $item['kode']);
                $itemSubtotal = $item['qty'] * $item['harga'];
                $subtotal += $itemSubtotal;

                $itemsToInsert[] = [
                    'product_id' => $product ? $product->id : $products->first()->id,
                    'nama_produk_snapshot' => $product ? $product->nama_produk : 'Produk Sablon',
                    'ukuran' => $item['ukuran'],
                    'jumlah' => $item['qty'],
                    'harga_snapshot' => $item['harga'],
                    'subtotal' => $itemSubtotal,
                    'catatan' => 'Catatan pesanan',
                ];
            }

            $transaction = Transaction::updateOrCreate(
                ['invoice_no' => $orderData['invoice_no']],
                [
                    'customer_id' => $customer->id,
                    'user_id' => $orderData['user']->id,
                    'tanggal_transaksi' => $orderData['tanggal'],
                    'deadline' => $orderData['deadline'],
                    'alamat_pengiriman' => $customer->alamat,
                    'biaya_pengiriman' => 0,
                    'diskon' => 0,
                    'refund' => 0,
                    'subtotal' => $subtotal,
                    'total_tagihan' => $subtotal,
                    'status_produksi' => $orderData['status_produksi'],
                    'status_pembayaran' => $orderData['status_pembayaran'],
                    'status_pengambilan' => $orderData['status_pengambilan'],
                    'catatan' => 'Pesanan rutin dari pelanggan.',
                ]
            );

            // Details
            $transaction->details()->delete();
            foreach ($itemsToInsert as $detail) {
                $transaction->details()->create($detail);
            }

            // Payments
            $transaction->payments()->delete();
            foreach ($orderData['payments'] as $payment) {
                $payMethod = PaymentMethod::where('tipe', $payment['metode'])->first();
                Payment::create([
                    'transaction_id' => $transaction->id,
                    'user_id' => $orderData['user']->id,
                    'payment_method_id' => $payMethod?->id,
                    'tanggal_bayar' => $payment['date'],
                    'jenis_bayar' => $payment['jenis'],
                    'metode_bayar' => $payment['metode'],
                    'jumlah_bayar' => $payment['amount'],
                    'keterangan' => 'Pembayaran '.strtoupper($payment['jenis']).($payMethod ? ' via '.$payMethod->nama_metode : ''),
                ]);
            }

            // Histories
            $transaction->statusHistories()->delete();
            $histTime = Carbon::parse($orderData['tanggal'])->startOfDay()->addHours(9);
            foreach ($orderData['history'] as $status) {
                TransactionStatusHistory::create([
                    'transaction_id' => $transaction->id,
                    'user_id' => $orderData['user']->id,
                    'status' => $status,
                    'waktu' => $histTime->copy(),
                    'catatan' => 'Status bergeser ke '.strtoupper($status),
                ]);
                $histTime->addDays(2);
            }
        }
    }
}
