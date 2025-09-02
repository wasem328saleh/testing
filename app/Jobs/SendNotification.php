<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Notifications\NotificationFCM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $noti = new NotificationFCM();
        $users = $this->data['users'];
        foreach ($users as $user) {
            try {
                // Your notification creation and sending code
//                $notify=Notification::create([
//                    'title' => $this->data['title'],
//                    'body' => $this->data['body'],
//                    'type' => $this->data['type'],
//                    'user_id' => $user['id'],
//                ]);
                echo nl2br("W :".$user->get('id'));
            $notify = $user->notifications()->create([
                'title' => $this->data['title'],
                'body' => $this->data['body'],
                'type' => $this->data['type'],
            ]);
                $noti->send_notify_mobile($user->device_tokens()->pluck('token')->toArray(), $notify);
            } catch (\Exception $e) {
                Log::error('Failed to send notification to user: ' . $user->get('id'), [
                    'error' => $e->getMessage()
                ]);
            }

        }
    }
}
