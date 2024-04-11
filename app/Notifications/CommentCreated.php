<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentCreated extends Notification
{
    use Queueable;

    public $user;
    public $comment_id;
    public $ad_id;
    public $comment_status;
    public $comment;
    public $ad_title;


    /**
     * Create a new notification instance.
     */
    public function __construct($user, $comment_id, $ad_id, $comment_status, $comment, $ad_title)
    {
        $this->user             = $user;
        $this->comment_id       = $comment_id;
        $this->ad_id          = $ad_id;
        $this->comment_status   = $comment_status;
        $this->comment          = $comment;
        $this->ad_title       = $ad_title;
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
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id'           => $this->user->id,
            'user_name'         => $this->user->name,
            'comment_id'        => $this->comment_id,
            'ad_id'           => $this->ad_id,
            'comment_status'    => $this->comment_status,
            'comment'           => $this->comment,
            'ad_title'        => $this->ad_title,
        ];
    }
}
