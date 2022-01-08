<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithBroadcasting;

class AddedNewComment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    use InteractsWithBroadcasting;

    public $post_id;
    public $comment_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($post_id, $comment_id)
    {
        $this->post_id = $post_id;
        $this->comment_id = $comment_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('post-channel.' . $this->post_id);
    }
}
