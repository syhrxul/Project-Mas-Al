<?php

namespace App\Services;

use App\Models\Rental;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

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

        // Jika menggunakan WhatsApp
        if ($rental->whatsapp_number) {
            // Format nomor WhatsApp
            $whatsappNumber = $rental->whatsapp_number;
            if (str_starts_with($whatsappNumber, '0')) {
                $whatsappNumber = '62' . substr($whatsappNumber, 1);
            }

            // Simpan PDF ke storage
            $filename = 'invoice-' . $rental->id . '.pdf';
            Storage::disk('public')->put('invoices/' . $filename, $pdf->output());

            // Format pesan WhatsApp
            $message = "Halo {$rental->user->name}! 👋\n\n";
            $message .= "Pesanan Anda telah disetujui. ✅\n";
            $message .= "Berikut adalah nota penyewaan Anda dari AL Management.\n\n";
            $message .= "Silakan unduh nota dalam format PDF melalui link berikut:\n";
            $message .= url('storage/invoices/' . $filename);

            // Kirim pesan WhatsApp dengan nomor yang sudah diformat
            $encodedMessage = urlencode($message);
            $waUrl = "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";

            return redirect()->away($waUrl);
        }
        
        // Jika menggunakan Email
        if ($rental->email) {
            Mail::send('emails.rental-invoice', [
                'rental' => $rental,
                'userName' => $rental->user->name,
            ], function ($message) use ($rental, $pdf) {
                $message->to($rental->email)
                    ->subject('Nota Penyewaan - AL Management')
                    ->attachData($pdf->output(), 'invoice.pdf');
            });

            \Log::info('Email terkirim ke: ' . $rental->email); // Tambahkan logging

            // Show notification untuk admin
            Notification::make()
                ->title('Invoice terkirim')
                ->body("Nota penyewaan telah dikirim ke email pelanggan.")
                ->success()
                ->send();

            return back();
        }
    }
}