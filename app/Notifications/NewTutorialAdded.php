<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewTutorialAdded extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $tutorialDetails;

    public function __construct($tutorialDetails)
    {
        $this->tutorialDetails = $tutorialDetails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function viaQueues()
    {
        return [
            'broadcast' => 'broadcast_queue',
            'database' => 'database_queue',
        ];
    }

    public function toDatabase($notifiable)
    {
        $course = $this->tutorialDetails->course;
        return [
            'message' => "A new tutorial has been added on $course->title",
            'link_to' => env('APP_URL') . "/show/course/$course->id",
            'icon_text' => "T",
            'icon_image' => $course->ownerDetails->profilePhoto,
            'created_at' => $this->tutorialDetails->created_at,
        ];
    }

    public function toBroadcast($notifiable)
    {
        $course = $this->tutorialDetails->course;
        return new BroadcastMessage([
            'message' => "A new tutorial has been added on $course->title",
            'link_to' => env('APP_URL') . "/show/course/$course->id",
            'icon_text' => "T",
            'icon_image' => $course->ownerDetails->profilePhoto,
            'created_at' => $this->tutorialDetails->created_at,
        ]);
    }
}
