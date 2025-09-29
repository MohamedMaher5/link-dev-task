<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
{
    use Queueable;

    protected Booking $booking;
    protected string $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, string $newStatus)
    {
        $this->booking = $booking;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Booking Status Updated: {$this->newStatus}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your booking #{$this->booking->id} has been updated.")
            ->line("New Status: **{$this->newStatus}**")
            ->line("Service: {$this->booking->service->name}")
            ->line("Start: {$this->booking->start_time}")
            ->line("End: {$this->booking->end_time}")
            ->action('View Booking', url("/bookings/{$this->booking->id}"))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
