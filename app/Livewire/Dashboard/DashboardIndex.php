<?php

namespace App\Livewire\Dashboard;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\ProductionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard — Lancar Barokah')]
class DashboardIndex extends Component
{
    public function markAsPickedUp(int $transactionId, ProductionService $service): void
    {
        $transaction = Transaction::find($transactionId);
        if ($transaction) {
            try {
                $service->markAsPickedUp($transaction);
                $this->dispatch('toast', message: "Pesanan {$transaction->invoice_no} berhasil ditandai SUDAH DIAMBIL.", type: 'success');
            } catch (\Exception $e) {
                $this->dispatch('toast', message: $e->getMessage(), type: 'error');
            }
        }
    }

    public function render()
    {
        $user = auth()->user();
        $isAdmin = $user && $user->isAdmin();
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Common Operational Metrics
        $transaksiHariIniCount = Transaction::whereDate('tanggal_transaksi', $today)->count();
        $inProductionCount = Transaction::where('status_produksi', '!=', 'selesai')->count();
        $readyToPickupCount = Transaction::where('status_produksi', 'selesai')
            ->where('status_pengambilan', 'belum_diambil')
            ->count();

        $overdueCount = Transaction::where('status_produksi', '!=', 'selesai')
            ->whereDate('deadline', '<', $today)
            ->count();

        $urgentOrdersCount = Transaction::where('status_produksi', '!=', 'selesai')
            ->whereDate('deadline', '<=', $today->copy()->addDays(2))
            ->count();

        // Production stage breakdown
        $stageCounts = [
            'cutting' => Transaction::where('status_produksi', 'cutting')->count(),
            'jahit' => Transaction::where('status_produksi', 'jahit')->count(),
            'printing' => Transaction::where('status_produksi', 'printing')->count(),
            'packing' => Transaction::where('status_produksi', 'packing')->count(),
            'selesai' => Transaction::where('status_produksi', 'selesai')->count(),
        ];
        $totalActiveProduction = array_sum(array_slice($stageCounts, 0, 4));

        // Upcoming nearest deadlines (prioritas kerja mendesak)
        $upcomingDeadlines = Transaction::with(['customer', 'details'])
            ->where('status_produksi', '!=', 'selesai')
            ->orderBy('deadline', 'asc')
            ->take(6)
            ->get();

        // Ready to pickup orders (barang sudah selesai, belum diambil pelanggan)
        $readyToPickupOrders = Transaction::with(['customer', 'details'])
            ->where('status_produksi', 'selesai')
            ->where('status_pengambilan', 'belum_diambil')
            ->latest('updated_at')
            ->take(6)
            ->get();

        // Recent Transactions
        $recentTransactions = Transaction::with(['customer', 'user', 'details'])
            ->latest('tanggal_transaksi')
            ->take(6)
            ->get();

        // Admin-Specific Financial & Business Intelligence Data
        $adminData = [];
        if ($isAdmin) {
            $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

            $omzetBulanIni = (float) Transaction::whereDate('tanggal_transaksi', '>=', $startOfMonth)->sum('total_tagihan');
            $omzetBulanLalu = (float) Transaction::whereBetween('tanggal_transaksi', [$lastMonthStart, $lastMonthEnd])->sum('total_tagihan');
            $omzetHariIni = (float) Transaction::whereDate('tanggal_transaksi', $today)->sum('total_tagihan');

            $kasBulanIni = (float) Payment::whereDate('tanggal_bayar', '>=', $startOfMonth)->sum('jumlah_bayar');
            $kasHariIni = (float) Payment::whereDate('tanggal_bayar', $today)->sum('jumlah_bayar');

            $growthPercent = 0.0;
            if ($omzetBulanLalu > 0) {
                $growthPercent = round((($omzetBulanIni - $omzetBulanLalu) / $omzetBulanLalu) * 100, 1);
            }

            // Piutang (Total Tagihan - Total Bayar)
            $unpaidTransactions = Transaction::where('status_pembayaran', '!=', 'lunas')->get();
            $totalTagihanUnpaid = (float) $unpaidTransactions->sum('total_tagihan');
            $totalBayarUnpaid = (float) Payment::whereIn('transaction_id', $unpaidTransactions->pluck('id'))->sum('jumlah_bayar');
            $piutangBelumLunas = max(0, $totalTagihanUnpaid - $totalBayarUnpaid);

            // 7-Day Order Volume Activity Bar Chart Data
            $activityDays = [];
            $maxDayVal = 1;
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $daySum = (float) Transaction::whereDate('tanggal_transaksi', $date)->sum('total_tagihan');
                if ($daySum > $maxDayVal) {
                    $maxDayVal = $daySum;
                }
                $activityDays[] = [
                    'day' => $date->translatedFormat('D'),
                    'date' => $date->format('d/m'),
                    'amount' => $daySum,
                    'count' => Transaction::whereDate('tanggal_transaksi', $date)->count(),
                ];
            }

            // Payment method breakdown (Cash vs Transfer vs QRIS) for this month
            $paymentMethodBreakdown = [
                'cash' => (float) Payment::where('metode_bayar', 'cash')->whereDate('tanggal_bayar', '>=', $startOfMonth)->sum('jumlah_bayar'),
                'transfer' => (float) Payment::where('metode_bayar', 'transfer')->whereDate('tanggal_bayar', '>=', $startOfMonth)->sum('jumlah_bayar'),
                'qris' => (float) Payment::where('metode_bayar', 'qris')->whereDate('tanggal_bayar', '>=', $startOfMonth)->sum('jumlah_bayar'),
            ];

            // Top Selling Products / Services this month
            $topProducts = TransactionDetail::select('nama_produk_snapshot', DB::raw('SUM(jumlah) as total_qty'), DB::raw('SUM(subtotal) as total_nominal'))
                ->whereHas('transaction', fn ($q) => $q->whereDate('tanggal_transaksi', '>=', $startOfMonth))
                ->groupBy('nama_produk_snapshot')
                ->orderByDesc('total_qty')
                ->take(5)
                ->get();

            $adminData = [
                'omzetBulanIni' => $omzetBulanIni,
                'omzetBulanLalu' => $omzetBulanLalu,
                'omzetHariIni' => $omzetHariIni,
                'growthPercent' => $growthPercent,
                'kasBulanIni' => $kasBulanIni,
                'kasHariIni' => $kasHariIni,
                'piutangBelumLunas' => $piutangBelumLunas,
                'activityDays' => $activityDays,
                'maxDayVal' => $maxDayVal,
                'paymentMethodBreakdown' => $paymentMethodBreakdown,
                'topProducts' => $topProducts,
            ];
        }

        return view('livewire.dashboard.dashboard-index', array_merge([
            'isAdmin' => $isAdmin,
            'transaksiHariIniCount' => $transaksiHariIniCount,
            'inProductionCount' => $inProductionCount,
            'readyToPickupCount' => $readyToPickupCount,
            'overdueCount' => $overdueCount,
            'urgentOrdersCount' => $urgentOrdersCount,
            'stageCounts' => $stageCounts,
            'totalActiveProduction' => $totalActiveProduction,
            'upcomingDeadlines' => $upcomingDeadlines,
            'readyToPickupOrders' => $readyToPickupOrders,
            'recentTransactions' => $recentTransactions,
        ], $adminData));
    }
}
