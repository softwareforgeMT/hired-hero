<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;


class GenericNotification extends Notification
{
   
    use Queueable;

    private $type;
    private $badgeType;
    private $title;
    private $message;
    private $link;

  

    public function __construct($type,$badgeType,$title, $message,$link = null)
    {   
        $this->type = $type;
        $this->badgeType = $badgeType;
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->type,
            'badgeType' => $this->badgeType,
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
        ];
    }

    // public function toBroadcast($notifiable)
    // {   
    //     \Log::info('Event fired!');
    //     return new BroadcastMessage([
    //         'title' => $this->title,
    //         'message' => $this->message,
    //     ], 'messages');
    // }

    
}
