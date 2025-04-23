<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class CommentCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $commenter;
    protected $comment_id;
    protected $ad_id;
    protected $comment_status;
    protected $comment;
    protected $ad_title;

    public function __construct(User $commenter, $comment_id, $ad_id, $comment_status, $comment, $ad_title)
    {
        $this->commenter = $commenter;
        $this->comment_id = $comment_id;
        $this->ad_id = $ad_id;
        $this->comment_status = $comment_status;
        $this->comment = $comment;
        $this->ad_title = $ad_title;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'commenter_id' => $this->commenter->id,
            'commenter_name' => $this->commenter->name,
            'comment_id' => $this->comment_id,
            'ad_id' => $this->ad_id,
            'comment_status' => $this->comment_status,
            'comment' => $this->comment,
            'ad_title' => $this->ad_title,
            'message' => "{$this->commenter->name} a commenté votre annonce: {$this->ad_title}"
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}