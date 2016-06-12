<?php

namespace Linku\Events;

use Linku\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Linku\Models\Invite;

class OnInviteCreated extends Event
{
    use SerializesModels;

    public $invite;

    /**
     * Create a new event instance.
     * @param Invite $invite
     */
    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
