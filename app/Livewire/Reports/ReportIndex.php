<?php

namespace App\Livewire\Reports;

use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('components.layouts.app')]
#[Title('Laporan Bisnis & Rekapitulasi')]
class ReportIndex extends Component
{
    public string $activeTab = 'transaksi'; // transaksi, omzet, kas, piutang, overdue

    public string $startDate = '';

    public string $endDate = '';

    public string $datePreset = 'month'; // today, week, month, last_month, year, custom

    public function mount(): void
    {
        $this->applyPreset('month');
    }

    public function applyPreset(string $preset): void
    {
        $this->datePreset = $preset;

        switch ($preset) {
            case 'today':
                $this->startDate = Carbon::today()->toDateString();
                $this->endDate = Carbon::today()->toDateString();
                break;
            case 'week':
                $this->startDate = Carbon::now()->startOfWeek()->toDateString();
                $this->endDate = Carbon::now()->endOfWeek()->toDateString();
                break;
            case 'month':
                $this->startDate = Carbon::now()->startOfMonth()->toDateString();
                $this->endDate = Carbon::now()->endOfMonth()->toDateString();
                break;
            case 'last_month':
                $this->startDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
                $this->endDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();
                break;
            case 'year':
                $this->startDate = Carbon::now()->startOfYear()->toDateString();
                $this->endDate = Carbon::now()->endOfYear()->toDateString();
                break;
        }
    }

    public function exportCsv(): StreamedResponse
    {
        $filename = "Laporan-{$this->activeTab}-{$this->startDate}-sd-{$this->endDate}.csv";

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel compatibility

            if ($this->activeTab === 'transaksi' || $this->activeTab === 'omzet') {
                fputcsv($handle, ['No. Invoice', 'Tanggal Transaksi', 'Target Deadline', 'Pelanggan', 'Status Produksi', 'Status Bayar', 'Subtotal', 'Ongkir', 'Diskon', 'Total Tagihan', 'Total Bayar', 'Sisa Tagihan']);

                $transactions = Transaction::with('customer')
                    ->whereDate('tanggal_transaksi', '>=', $this->startDate)
                    ->whereDate('tanggal_transaksi', '<=', $this->endDate)
                    ->orderBy('tanggal_transaksi', 'asc')
                    ->get();

                foreach ($transactions as $t) {
                    fputcsv($handle, [
                        $t->invoice_no,
                        $t->tanggal_transaksi->format('Y-m-d'),
                        $t->deadline->format('Y-m-d'),
                        $t->customer->nama ?? '-',
                        $t->status_produksi,
                        $t->status_pembayaran,
                        $t->subtotal,
                        $t->biaya_pengiriman,
                        $t->diskon,
                        $t->total_tagihan,
                        $t->total_bayar,
                        $t->sisa_tagihan,
                    ]);
                }
            } elseif ($this->activeTab === 'kas') {
                fputcsv($handle, ['Tanggal Bayar', 'Invoice', 'Pelanggan', 'Jenis Pembayaran', 'Metode Bayar', 'Jumlah Bayar (Rp)', 'Keterangan', 'Petugas']);

                $payments = Payment::with(['transaction.customer', 'user'])
                    ->whereDate('tanggal_bayar', '>=', $this->startDate)
                    ->whereDate('tanggal_bayar', '<=', $this->endDate)
                    ->orderBy('tanggal_bayar', 'asc')
                    ->get();

                foreach ($payments as $p) {
                    fputcsv($handle, [
                        $p->tanggal_bayar->format('Y-m-d'),
                        $p->transaction->invoice_no ?? '-',
                        $p->transaction->customer->nama ?? '-',
                        $p->jenis_bayar,
                        $p->metode_bayar,
                        $p->jumlah_bayar,
                        $p->keterangan ?? '-',
                        $p->user->name ?? '-',
                    ]);
                }
            } elseif ($this->activeTab === 'piutang') {
                fputcsv($handle, ['No. Invoice', 'Tanggal Transaksi', 'Deadline', 'Pelanggan', 'No. HP', 'Total Tagihan', 'Telah Dibayar', 'Sisa Piutang']);

                $unpaid = Transaction::with('customer')
                    ->where('status_pembayaran', '!=', 'lunas')
                    ->orderBy('tanggal_transaksi', 'asc')
                    ->get();

                foreach ($unpaid as $t) {
                    fputcsv($handle, [
                        $t->invoice_no,
                        $t->tanggal_transaksi->format('Y-m-d'),
                        $t->deadline->format('Y-m-d'),
                        $t->customer->nama ?? '-',
                        $t->customer->no_hp ?? '-',
                        $t->total_tagihan,
                        $t->total_bayar,
                        $t->sisa_tagihan,
                    ]);
                }
            } else { // overdue
                fputcsv($handle, ['No. Invoice', 'Tanggal Transaksi', 'Deadline Lewat', 'Pelanggan', 'No. HP', 'Status Produksi', 'Status Bayar', 'Total Tagihan']);

                $overdue = Transaction::with('customer')
                    ->where('status_produksi', '!=', 'selesai')
                    ->whereDate('deadline', '<', Carbon::today())
                    ->orderBy('deadline', 'asc')
                    ->get();

                foreach ($overdue as $t) {
                    fputcsv($handle, [
                        $t->invoice_no,
                        $t->tanggal_transaksi->format('Y-m-d'),
                        $t->deadline->format('Y-m-d'),
                        $t->customer->nama ?? '-',
                        $t->customer->no_hp ?? '-',
                        $t->status_produksi,
                        $t->status_pembayaran,
                        $t->total_tagihan,
                    ]);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render()
    {
        $transactionsQuery = Transaction::query()
            ->with(['customer', 'payments'])
            ->whereDate('tanggal_transaksi', '>=', $this->startDate)
            ->whereDate('tanggal_transaksi', '<=', $this->endDate);

        $paymentsQuery = Payment::query()
            ->with(['transaction.customer', 'user'])
            ->whereDate('tanggal_bayar', '>=', $this->startDate)
            ->whereDate('tanggal_bayar', '<=', $this->endDate);

        // Metrics for summary
        $totalOmzet = (float) $transactionsQuery->sum('total_tagihan');
        $totalOrders = $transactionsQuery->count();
        $avgOrderValue = $totalOrders > 0 ? ($totalOmzet / $totalOrders) : 0;

        $totalKas = (float) $paymentsQuery->sum('jumlah_bayar');

        $piutangQuery = Transaction::with('customer')->where('status_pembayaran', '!=', 'lunas');
        $totalPiutangCount = $piutangQuery->count();

        $overdueQuery = Transaction::with('customer')
            ->where('status_produksi', '!=', 'selesai')
            ->whereDate('deadline', '<', Carbon::today());
        $totalOverdueCount = $overdueQuery->count();

        // Data collection based on active tab
        if ($this->activeTab === 'kas') {
            $data = $paymentsQuery->latest('tanggal_bayar')->get();
        } elseif ($this->activeTab === 'piutang') {
            $data = $piutangQuery->latest('tanggal_transaksi')->get();
        } elseif ($this->activeTab === 'overdue') {
            $data = $overdueQuery->orderBy('deadline', 'asc')->get();
        } else {
            $data = $transactionsQuery->latest('tanggal_transaksi')->get();
        }

        return view('livewire.reports.report-index', [
            'data' => $data,
            'totalOmzet' => $totalOmzet,
            'totalOrders' => $totalOrders,
            'avgOrderValue' => $avgOrderValue,
            'totalKas' => $totalKas,
            'totalPiutangCount' => $totalPiutangCount,
            'totalOverdueCount' => $totalOverdueCount,
        ]);
    }
}
