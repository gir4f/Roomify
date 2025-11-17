<?php

namespace App\Mail;

use App\Models\Booking; // Import Booking
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $status; // 'approved' atau 'rejected'

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, string $status)
    {
        $this->booking = $booking;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Buat subject email dinamis
        $subject = $this->status === 'approved' 
            ? 'Booking Ruangan Anda Telah Disetujui' 
            : 'Booking Ruangan Anda Ditolak';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Arahkan ke satu template Blade
        return new Content(
            view: 'emails.bookings.index', // Path ke template gabungan
            with: [
                'booking' => $this->booking,
                'status' => $this->status, // Kirim status ke Blade
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}