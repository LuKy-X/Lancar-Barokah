<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Display a clean HTML invoice preview in the browser.
     */
    public function preview(Transaction $transaction)
    {
        $transaction->load(['customer', 'user', 'details.product', 'payments.user']);
        $company = CompanyProfile::current();

        return view('invoices.preview', [
            'transaction' => $transaction,
            'company' => $company,
        ]);
    }

    /**
     * Display a print-optimized invoice view that automatically triggers the browser print dialog.
     */
    public function print(Transaction $transaction)
    {
        $transaction->load(['customer', 'user', 'details.product', 'payments.user']);
        $company = CompanyProfile::current();

        return view('invoices.print', [
            'transaction' => $transaction,
            'company' => $company,
        ]);
    }

    /**
     * Download the invoice as a formatted PDF file using DomPDF.
     */
    public function download(Transaction $transaction): Response
    {
        $transaction->load(['customer', 'user', 'details.product', 'payments.user']);
        $company = CompanyProfile::current();

        $pdf = Pdf::loadView('invoices.pdf', [
            'transaction' => $transaction,
            'company' => $company,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("Invoice-{$transaction->invoice_no}.pdf");
    }
}
