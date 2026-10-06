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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->date('tanggal_bayar');
            $table->enum('jenis_bayar', ['dp', 'cicilan', 'pelunasan']);
            $table->enum('metode_bayar', ['cash', 'transfer', 'qris']);
            $table->decimal('jumlah_bayar', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('tanggal_bayar');
            $table->index('payment_method_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
