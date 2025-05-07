<?php

namespace App\Services;

use App\Models\Rental;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateAndSendInvoice(Rental $rental)
    {
        // Generate PDF
        $pdf = Pdf::loadView('pdf.rental-invoice', [
            'rental' => $rental,
            'date' => now()->format('d M Y H:i'),
            'product' => $rental->product,
            'start_date' => Carbon::parse($rental->start_datetime)->format('d M Y H:i'),
            'end_date' => Carbon::parse($rental->end_datetime)->format('d M Y H:i'),
        ]);

        // Simpan PDF ke storage
        $filename = 'invoice-' . $rental->id . '.pdf';
        Storage::disk('public')->put('invoices/' . $filename, $pdf->output());

        // Format pesan WhatsApp
        $message = "Halo {$rental->user->name}! 👋\n\n";
        $message .= "Pesanan Anda telah disetujui. ✅\n";
        $message .= "Berikut adalah nota penyewaan Anda dari AL Management.\n\n";
        $message .= "Silakan unduh nota dalam format PDF melalui link berikut:\n";
        $message .= url('storage/invoices/' . $filename);

        // Kirim pesan WhatsApp
        $encodedMessage = urlencode($message);
        $waUrl = "https://wa.me/{$rental->whatsapp_number}?text={$encodedMessage}";

        return redirect()->away($waUrl);
    }
}