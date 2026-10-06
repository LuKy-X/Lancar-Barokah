<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaction->invoice_no }} — {{ $company->nama_usaha }}</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-100 font-sans p-4 sm:p-8 text-slate-900 antialiased">
    @php
        $dpAmount = (float) $transaction->payments->where('jenis_bayar', 'dp')->sum('jumlah_bayar');
        $installments = $transaction->payments->whereIn('jenis_bayar', ['cicilan', 'pelunasan'])->values();
        $cicilan1 = $installments->get(0)?->jumlah_bayar;
        $cicilan2 = $installments->get(1)?->jumlah_bayar;
        $totalQty = $transaction->details->sum('jumlah');
    @endphp

    <div class="max-w-4xl mx-auto space-y-4">
        <!-- Floating Actions Bar -->
        <div class="flex items-center justify-between bg-white px-5 py-3 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-600">Pratinjau Nota / Faktur Invoice</span>
            <div class="flex items-center gap-2">
                <a href="{{ route('invoices.print', $transaction) }}" target="_blank" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-colors">
                    Cetak Nota
                </a>
                <a href="{{ route('invoices.download', $transaction) }}" class="px-4 py-2 bg-[#0e3f32] hover:bg-[#124d3e] text-white text-xs font-bold rounded-xl transition-colors">
                    Unduh PDF
                </a>
            </div>
        </div>

        <!-- Printable Document Canvas (Matching PDF sample) -->
        <div class="bg-white p-6 sm:p-10 rounded-2xl border border-slate-300 shadow-lg text-[11px] text-slate-900">
            
            <!-- Header Section -->
            <div class="flex items-start justify-between gap-4 pb-4">
                <!-- Left: Company Profile -->
                <div>
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo-icon.png') }}" alt="Logo Lancar Barokah" class="w-10 h-10 object-contain">
                        <div>
                            <h1 class="text-base font-black tracking-tight text-slate-900 leading-none">
                                {{ strtoupper($company->nama_usaha) }}
                            </h1>
                            <span class="text-[9px] font-bold tracking-widest text-[#0e3f32] uppercase">SABLON & KONVEKSI</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-700 mt-2">{{ $company->alamat }}</p>
                    <p class="text-[11px] text-slate-700">Telp/SMS/Whatsapp : {{ $company->whatsapp ?? $company->telepon ?? '085642233904' }}</p>
                    <p class="text-[11px] text-blue-600 underline">{{ $company->email ?? 'lancarbarokah.order@gmail.com' }}</p>
                </div>

                <!-- Right: Invoice Title & Meta Box -->
                <div class="w-64 shrink-0 text-right">
                    <h2 class="text-2xl font-black tracking-wider text-slate-900 uppercase">INVOICE</h2>
                    <table class="w-full mt-2 border-collapse border border-slate-900 text-[11px] text-left">
                        <tr>
                            <td class="border border-slate-900 px-2.5 py-1 font-bold bg-slate-50 w-24">Nomor</td>
                            <td class="border border-slate-900 px-2.5 py-1 font-mono font-bold">{{ $transaction->invoice_no }}</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-900 px-2.5 py-1 font-bold bg-slate-50">Tanggal</td>
                            <td class="border border-slate-900 px-2.5 py-1">{{ $transaction->tanggal_transaksi->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-900 px-2.5 py-1 font-bold bg-slate-50">Deadline</td>
                            <td class="border border-slate-900 px-2.5 py-1 font-bold text-[#0e3f32]">{{ $transaction->deadline ? $transaction->deadline->format('d F Y') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- 3-Column Middle Section: Ditagihkan Kepada, Rincian Pembayaran, Rekening Bank -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 my-4">
                
                <!-- Column 1: Ditagihkan Kepada & Status (4 cols) -->
                <div class="md:col-span-4 flex flex-col justify-between">
                    <div>
                        <div class="font-bold text-[11px] mb-1">Ditagihkan Kepada :</div>
                        <table class="w-full border-collapse border border-slate-900 text-[11px]">
                            <tr>
                                <td class="border border-slate-900 px-2 py-1 font-bold bg-slate-50 w-20">Customer</td>
                                <td class="border border-slate-900 px-2 py-1 font-bold uppercase">{{ $transaction->customer->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-900 px-2 py-1 font-bold bg-slate-50">Alamat</td>
                                <td class="border border-slate-900 px-2 py-1">{{ $transaction->alamat_pengiriman ?? $transaction->customer->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-900 px-2 py-1 font-bold bg-slate-50">Kontak</td>
                                <td class="border border-slate-900 px-2 py-1">{{ $transaction->customer->no_hp ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Big Status Box -->
                    <div class="mt-3">
                        <div class="font-bold text-[11px] mb-1">Status:</div>
                        <div class="border-2 border-slate-900 py-2.5 px-4 text-center font-black text-sm tracking-wider uppercase {{ $transaction->status_pembayaran === 'lunas' ? 'bg-emerald-100 text-emerald-900' : 'bg-slate-200 text-slate-900' }}">
                            {{ $transaction->status_pembayaran === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                        </div>
                    </div>
                </div>

                <!-- Column 2: Rincian Pembayaran (5 cols) -->
                <div class="md:col-span-5">
                    <div class="font-bold text-[11px] mb-1">Rincian Pembayaran :</div>
                    <table class="w-full border-collapse border border-slate-900 text-[11px]">
                        <tr>
                            <td class="border border-slate-900 px-2 py-0.5 font-bold">Total</td>
                            <td class="border border-slate-900 px-1 py-0.5 text-center font-bold w-7">Rp</td>
                            <td class="border border-slate-900 px-2 py-0.5 text-right font-mono font-bold">{{ number_format($transaction->subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-900 px-2 py-0.5">Biaya pengiriman</td>
                            <td class="border border-slate-900 px-1 py-0.5 text-center w-7">Rp</td>
                            <td class="border border-slate-900 px-2 py-0.5 text-right font-mono">{{ $transaction->biaya_pengiriman > 0 ? number_format($transaction->biaya_pengiriman, 2, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-900 px-2 py-0.5">Diskon/refund</td>
                            <td class="border border-slate-900 px-1 py-0.5 text-center w-7">Rp</td>
                            <td class="border border-slate-900 px-2 py-0.5 text-right font-mono">{{ ($transaction->diskon + $transaction->refund) > 0 ? number_format(($transaction->diskon + $transaction->refund), 2, ',', '.') : '-' }}</td>
                        </tr>
                        <tr class="font-bold bg-slate-50">
                            <td class="border border-slate-900 px-2 py-0.5">Total Tagihan</td>
                            <td class="border border-slate-900 px-1 py-0.5 text-center font-bold w-7">Rp</td>
                            <td class="border border-slate-900 px-2 py-0.5 text-right font-mono font-black">{{ number_format($transaction->total_tagihan, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-900 px-2 py-0.5">DP</td>
                            <td class="border border-slate-900 px-1 py-0.5 text-center w-7">Rp</td>
                            <td class="border border-slate-900 px-2 py-0.5 text-right font-mono">{{ $dpAmount > 0 ? number_format($dpAmount, 2, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-900 px-2 py-0.5">Cicilan 1</td>
                            <td class="border border-slate-900 px-1 py-0.5 text-center w-7">Rp</td>
                            <td class="border border-slate-900 px-2 py-0.5 text-right font-mono">{{ $cicilan1 ? number_format((float) $cicilan1, 2, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-900 px-2 py-0.5">Cicilan 2</td>
                            <td class="border border-slate-900 px-1 py-0.5 text-center w-7">Rp</td>
                            <td class="border border-slate-900 px-2 py-0.5 text-right font-mono">{{ $cicilan2 ? number_format((float) $cicilan2, 2, ',', '.') : '-' }}</td>
                        </tr>
                        <tr class="bg-slate-300 font-black text-slate-950">
                            <td class="border border-slate-900 px-2 py-1 text-sm">Sisa</td>
                            <td class="border border-slate-900 px-1 py-1 text-center font-black w-7">Rp</td>
                            <td class="border border-slate-900 px-2 py-1 text-right font-mono text-sm font-black">{{ number_format($transaction->sisa_tagihan, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Column 3: Rekening Pembayaran (3 cols) -->
                <div class="md:col-span-3 space-y-2">
                    <!-- Bank BCA -->
                    <div class="bg-[#fef08a] border border-slate-900 p-2.5 rounded-sm shadow-2xs">
                        <div class="font-black text-xs text-slate-900">BANK BCA</div>
                        <div class="font-mono font-bold text-xs text-slate-950 tracking-wider">7850760882</div>
                        <div class="font-bold text-[10px] text-slate-800 uppercase">ALFIYAN NUR ROHMAN</div>
                    </div>

                    <!-- Bank Mandiri -->
                    <div class="bg-[#fef08a] border border-slate-900 p-2.5 rounded-sm shadow-2xs">
                        <div class="font-black text-xs text-slate-900">BANK MANDIRI</div>
                        <div class="font-mono font-bold text-xs text-slate-950 tracking-wider">1380026000062</div>
                        <div class="font-bold text-[10px] text-slate-800 uppercase">IRFAN WAHYU INDRAWAN</div>
                    </div>
                </div>

            </div>

            <!-- Rincian Order Table -->
            <div class="mt-4">
                <div class="text-center font-bold text-[11px] border border-b-0 border-slate-900 py-1 bg-slate-50 uppercase tracking-wider">
                    Rincian Order
                </div>
                <table class="w-full border-collapse border border-slate-900 text-[11px]">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-900 font-bold text-center">
                            <th class="border border-slate-900 py-1 px-2 w-10">No</th>
                            <th class="border border-slate-900 py-1 px-3 text-left">Nama Barang</th>
                            <th class="border border-slate-900 py-1 px-2 w-14">Qty</th>
                            <th class="border border-slate-900 py-1 px-3 w-32 text-right">Harga</th>
                            <th class="border border-slate-900 py-1 px-3 w-36 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->details as $idx => $detail)
                            <tr>
                                <td class="border border-slate-900 py-1 px-2 text-center">{{ $idx + 1 }}</td>
                                <td class="border border-slate-900 py-1 px-3 font-semibold uppercase">
                                    {{ $detail->nama_produk_snapshot }}
                                    @if($detail->ukuran) ({{ $detail->ukuran }}) @endif
                                    @if($detail->catatan)
                                        <span class="text-[10px] text-slate-500 lowercase block font-normal italic">{{ $detail->catatan }}</span>
                                    @endif
                                </td>
                                <td class="border border-slate-900 py-1 px-2 text-center font-bold">{{ $detail->jumlah }}</td>
                                <td class="border border-slate-900 py-1 px-3 text-right font-mono">
                                    Rp {{ number_format($detail->harga_snapshot, 2, ',', '.') }}
                                </td>
                                <td class="border border-slate-900 py-1 px-3 text-right font-mono font-bold">
                                    Rp {{ number_format($detail->subtotal, 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach

                        {{-- Empty rows for standard invoice spacing if details are few --}}
                        @for($i = count($transaction->details); $i < 6; $i++)
                            <tr>
                                <td class="border border-slate-900 py-2 px-2 text-center">&nbsp;</td>
                                <td class="border border-slate-900 py-2 px-3">&nbsp;</td>
                                <td class="border border-slate-900 py-2 px-2 text-center">&nbsp;</td>
                                <td class="border border-slate-900 py-2 px-3 text-right font-mono text-slate-300">Rp -</td>
                                <td class="border border-slate-900 py-2 px-3 text-right font-mono text-slate-300">Rp -</td>
                            </tr>
                        @endfor

                        <!-- Summary Row -->
                        <tr class="font-black bg-slate-100">
                            <td colspan="2" class="border border-slate-900 py-1.5 px-3 text-right uppercase">Total :</td>
                            <td class="border border-slate-900 py-1.5 px-2 text-center font-black">{{ $totalQty }}</td>
                            <td class="border border-slate-900 py-1.5 px-3 text-right font-mono font-black" colspan="2">
                                Rp {{ number_format($transaction->subtotal, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Bottom Signatures -->
            <div class="grid grid-cols-2 pt-8 text-center text-[11px] font-bold">
                <div>
                    <p>Customer</p>
                    <div class="h-16"></div>
                    <p class="uppercase font-bold tracking-wider underline">
                        {{ $transaction->customer->nama ?? 'FERRY' }}
                    </p>
                </div>
                <div>
                    <p>Customer Service</p>
                    <div class="h-16"></div>
                    <p class="uppercase font-bold tracking-wider underline">
                        {{ $transaction->user->name ?? 'AMELIA FRISKA' }}
                    </p>
                </div>
            </div>

            @if($company->footer_invoice)
                <div class="mt-6 pt-3 border-t border-slate-200 text-[10px] text-slate-500 whitespace-pre-line leading-relaxed">
                    {{ $company->footer_invoice }}
                </div>
            @endif

        </div>
    </div>
</body>
</html>
