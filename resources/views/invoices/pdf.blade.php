<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice-{{ $transaction->invoice_no }}</title>
    <style>
        @page {
            margin: 0.8cm 1cm;
            size: a4 portrait;
        }
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            color: #000000;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .bordered-table th, 
        .bordered-table td {
            border: 1px solid #000000;
            padding: 3px 5px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .bg-light { background-color: #f1f5f9; }
        .bg-yellow { background-color: #fef08a; }
        .bg-gray { background-color: #d1d5db; }
        .bank-box {
            background-color: #fef08a;
            border: 1px solid #000000;
            padding: 5px 7px;
            margin-bottom: 7px;
        }
        .status-box {
            border: 2px solid #000000;
            padding: 6px;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 5px;
            background-color: #e5e7eb;
        }
        .status-box.lunas {
            background-color: #dcfce7;
            color: #065f46;
        }
    </style>
</head>
<body>
    @php
        $dpAmount = (float) $transaction->payments->where('jenis_bayar', 'dp')->sum('jumlah_bayar');
        $installments = $transaction->payments->whereIn('jenis_bayar', ['cicilan', 'pelunasan'])->values();
        $cicilan1 = $installments->get(0)?->jumlah_bayar;
        $cicilan2 = $installments->get(1)?->jumlah_bayar;
        $totalQty = $transaction->details->sum('jumlah');
    @endphp

    <!-- Header Table -->
    <table style="margin-bottom: 12px;">
        <tr>
            <!-- Left: Company Info -->
            <td style="width: 60%; vertical-align: top;">
                <table style="width: auto; border: none; margin-bottom: 4px;">
                    <tr>
                        <td style="border: none; padding: 0 8px 0 0; vertical-align: middle;">
                            @if(file_exists(public_path('images/logo-icon.png')))
                                <img src="{{ public_path('images/logo-icon.png') }}" style="height: 38px; width: auto;" alt="Logo">
                            @endif
                        </td>
                        <td style="border: none; padding: 0; vertical-align: middle;">
                            <div style="font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">
                                {{ $company->nama_usaha }}
                            </div>
                            <div style="font-size: 8px; font-weight: bold; color: #0e3f32;">
                                SABLON & KONVEKSI
                            </div>
                        </td>
                    </tr>
                </table>
                <div style="font-size: 9.5px; color: #1e293b;">
                    {{ $company->alamat }}<br>
                    Telp/SMS/Whatsapp : {{ $company->whatsapp ?? $company->telepon ?? '085642233904' }}<br>
                    <span style="color: #2563eb; text-decoration: underline;">{{ $company->email ?? 'lancarbarokah.order@gmail.com' }}</span>
                </div>
            </td>

            <!-- Right: Invoice Title & Meta Box -->
            <td style="width: 40%; vertical-align: top; text-align: right;">
                <div style="font-size: 18px; font-weight: bold; letter-spacing: 1px; margin-bottom: 4px;">
                    INVOICE
                </div>
                <table class="bordered-table" style="width: 220px; float: right; font-size: 9px;">
                    <tr>
                        <td class="bg-light font-bold" style="width: 70px;">Nomor</td>
                        <td class="font-bold">{{ $transaction->invoice_no }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light font-bold">Tanggal</td>
                        <td>{{ $transaction->tanggal_transaksi->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light font-bold">Deadline</td>
                        <td class="font-bold">{{ $transaction->deadline ? $transaction->deadline->format('d F Y') : '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- 3-Column Middle Section Table -->
    <table style="margin-bottom: 12px;">
        <tr>
            <!-- Column 1: Ditagihkan Kepada (33%) -->
            <td style="width: 32%; vertical-align: top; padding-right: 8px;">
                <div class="font-bold" style="margin-bottom: 3px;">Ditagihkan Kepada :</div>
                <table class="bordered-table" style="font-size: 9px;">
                    <tr>
                        <td class="bg-light font-bold" style="width: 60px;">Customer</td>
                        <td class="font-bold uppercase">{{ $transaction->customer->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light font-bold">Alamat</td>
                        <td>{{ $transaction->alamat_pengiriman ?? $transaction->customer->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light font-bold">Kontak</td>
                        <td>{{ $transaction->customer->no_hp ?? '-' }}</td>
                    </tr>
                </table>

                <div style="margin-top: 8px;">
                    <div class="font-bold" style="margin-bottom: 2px;">Status:</div>
                    <div class="status-box {{ $transaction->status_pembayaran === 'lunas' ? 'lunas' : '' }}">
                        {{ $transaction->status_pembayaran === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                    </div>
                </div>
            </td>

            <!-- Column 2: Rincian Pembayaran (42%) -->
            <td style="width: 42%; vertical-align: top; padding: 0 4px;">
                <div class="font-bold" style="margin-bottom: 3px;">Rincian Pembayaran :</div>
                <table class="bordered-table" style="font-size: 9px;">
                    <tr>
                        <td class="font-bold">Total</td>
                        <td class="text-center font-bold" style="width: 22px;">Rp</td>
                        <td class="text-right font-bold">{{ number_format($transaction->subtotal, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Biaya pengiriman</td>
                        <td class="text-center" style="width: 22px;">Rp</td>
                        <td class="text-right">{{ $transaction->biaya_pengiriman > 0 ? number_format($transaction->biaya_pengiriman, 2, ',', '.') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Diskon/refund</td>
                        <td class="text-center" style="width: 22px;">Rp</td>
                        <td class="text-right">{{ ($transaction->diskon + $transaction->refund) > 0 ? number_format(($transaction->diskon + $transaction->refund), 2, ',', '.') : '-' }}</td>
                    </tr>
                    <tr class="bg-light font-bold">
                        <td>Total Tagihan</td>
                        <td class="text-center font-bold" style="width: 22px;">Rp</td>
                        <td class="text-right font-bold">{{ number_format($transaction->total_tagihan, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>DP</td>
                        <td class="text-center" style="width: 22px;">Rp</td>
                        <td class="text-right">{{ $dpAmount > 0 ? number_format($dpAmount, 2, ',', '.') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Cicilan 1</td>
                        <td class="text-center" style="width: 22px;">Rp</td>
                        <td class="text-right">{{ $cicilan1 ? number_format((float) $cicilan1, 2, ',', '.') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Cicilan 2</td>
                        <td class="text-center" style="width: 22px;">Rp</td>
                        <td class="text-right">{{ $cicilan2 ? number_format((float) $cicilan2, 2, ',', '.') : '-' }}</td>
                    </tr>
                    <tr class="bg-gray font-bold">
                        <td style="font-size: 10px;">Sisa</td>
                        <td class="text-center font-bold" style="width: 22px; font-size: 10px;">Rp</td>
                        <td class="text-right font-bold" style="font-size: 10px;">{{ number_format($transaction->sisa_tagihan, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </td>

            <!-- Column 3: Rekening Pembayaran (26%) -->
            <td style="width: 26%; vertical-align: top; padding-left: 8px;">
                <div style="height: 15px;"></div>
                <div class="bank-box">
                    <div style="font-weight: bold; font-size: 10px;">BANK BCA</div>
                    <div style="font-weight: bold; font-size: 10.5px; letter-spacing: 0.5px;">7850760882</div>
                    <div style="font-size: 8.5px; text-transform: uppercase;">ALFIYAN NUR ROHMAN</div>
                </div>

                <div class="bank-box">
                    <div style="font-weight: bold; font-size: 10px;">BANK MANDIRI</div>
                    <div style="font-weight: bold; font-size: 10.5px; letter-spacing: 0.5px;">1380026000062</div>
                    <div style="font-size: 8.5px; text-transform: uppercase;">IRFAN WAHYU INDRAWAN</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Rincian Order Table -->
    <div style="margin-top: 8px;">
        <div class="bg-light font-bold text-center uppercase" style="border: 1px solid #000; border-bottom: none; padding: 3px; font-size: 9px; letter-spacing: 0.5px;">
            Rincian Order
        </div>
        <table class="bordered-table" style="font-size: 9px;">
            <thead>
                <tr class="bg-light font-bold text-center">
                    <th style="width: 30px;">No</th>
                    <th style="text-align: left; padding-left: 6px;">Nama Barang</th>
                    <th style="width: 45px;">Qty</th>
                    <th style="width: 105px; text-align: right;">Harga</th>
                    <th style="width: 115px; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $idx => $detail)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="uppercase font-bold" style="padding-left: 6px;">
                            {{ $detail->nama_produk_snapshot }}
                            @if($detail->ukuran) ({{ $detail->ukuran }}) @endif
                            @if($detail->catatan)
                                <div style="font-size: 8px; color: #64748b; font-weight: normal; font-style: italic;">{{ $detail->catatan }}</div>
                            @endif
                        </td>
                        <td class="text-center font-bold">{{ $detail->jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga_snapshot, 2, ',', '.') }}</td>
                        <td class="text-right font-bold">Rp {{ number_format($detail->subtotal, 2, ',', '.') }}</td>
                    </tr>
                @endforeach

                {{-- Empty filler rows if items are few --}}
                @for($i = count($transaction->details); $i < 6; $i++)
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-right" style="color: #94a3b8;">Rp -</td>
                        <td class="text-right" style="color: #94a3b8;">Rp -</td>
                    </tr>
                @endfor

                <!-- Summary Row -->
                <tr class="bg-light font-bold">
                    <td colspan="2" class="text-right uppercase font-bold">Total :</td>
                    <td class="text-center font-bold">{{ $totalQty }}</td>
                    <td colspan="2" class="text-right font-bold">
                        Rp {{ number_format($transaction->subtotal, 2, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Signatures -->
    <table style="margin-top: 30px; text-align: center; font-size: 9.5px;">
        <tr>
            <td style="width: 50%;">
                <div class="font-bold">Customer</div>
                <div style="height: 50px;"></div>
                <div class="font-bold uppercase" style="text-decoration: underline;">
                    {{ $transaction->customer->nama ?? 'FERRY' }}
                </div>
            </td>
            <td style="width: 50%;">
                <div class="font-bold">Customer Service</div>
                <div style="height: 50px;"></div>
                <div class="font-bold uppercase" style="text-decoration: underline;">
                    {{ $transaction->user->name ?? 'AMELIA FRISKA' }}
                </div>
            </td>
        </tr>
    </table>

    @if($company->footer_invoice)
        <div style="margin-top: 20px; padding-top: 6px; border-top: 1px solid #cbd5e1; font-size: 8px; color: #64748b; line-height: 1.3;">
            {!! nl2br(e($company->footer_invoice)) !!}
        </div>
    @endif

</body>
</html>
