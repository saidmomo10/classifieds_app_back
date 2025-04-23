<?php

namespace App\Notifications;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ad;
    protected $user;

    public function __construct(Ad $ad, User $user)
    {
        $this->ad = $ad;
        $this->user = $user;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'ad_id' => $this->ad->id,
            'ad_title' => $this->ad->title,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'message' => "L'utilisateur {$this->user->name} a publié une nouvelle annonce: {$this->ad->title}"
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}