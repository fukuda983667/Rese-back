<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '予約のリマインダー',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_reminder'
        );
    }

    public function build()
    {
        return $this->view('emails.reservation_reminder')
                    ->with(['reservation' => $this->reservation]);
    }
}