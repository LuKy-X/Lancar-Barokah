<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->date('tanggal_transaksi');
            $table->date('deadline');
            $table->text('alamat_pengiriman')->nullable();
            $table->decimal('biaya_pengiriman', 15, 2)->default(0);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('refund', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total_tagihan', 15, 2)->default(0);
            $table->enum('status_produksi', ['cutting', 'jahit', 'printing', 'packing', 'selesai'])->default('cutting');
            $table->json('tahapan_produksi')->nullable();
            $table->enum('status_pembayaran', ['belum_bayar', 'dp', 'cicilan', 'lunas'])->default('belum_bayar');
            $table->enum('status_pengambilan', ['belum_diambil', 'diambil'])->default('belum_diambil');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('tanggal_transaksi');
            $table->index('deadline');
            $table->index('status_produksi');
            $table->index('status_pembayaran');
            $table->index('status_pengambilan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
