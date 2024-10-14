<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationReminderMail;
use App\Models\Reservation;
use Carbon\Carbon;

class SendReservationReminders extends Command
{
    protected $signature = 'reservations:send-reminders';
    protected $description = 'Send reservation reminders to users';

    public function handle()
    {
        // 今日の予約を取得
        $today = Carbon::now()->format('Y-m-d');
        $reservations = Reservation::whereDate('reservation_time', $today)->with('user')->get();

        foreach ($reservations as $reservation) {
            Mail::to($reservation->user->email)->send(new ReservationReminderMail($reservation));
        }

        $this->info('Reservation reminders sent successfully!');
    }
}