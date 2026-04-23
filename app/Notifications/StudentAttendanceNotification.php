<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudentAttendanceNotification extends Notification
{
    use Queueable;

    public $details;

    /**
     * Create a new notification instance.
     * 
     * @param array $details ['title', 'message', 'type', 'student_name', 'status']
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->details['title'],
            'message' => $this->details['message'],
            'type' => $this->details['type'] ?? 'info', // e.g., 'success', 'warning', 'info'
            'student_name' => $this->details['student_name'] ?? null,
            'status' => $this->details['status'] ?? null,
            'time' => now()->format('H:i'),
        ];
    }
}
