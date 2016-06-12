<?php

namespace Linku\Listeners;

use Linku\Events\OnInviteCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Linku\Linku\Mailer;

class SendInviteEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OnInviteCreated  $event
     * @return void
     */
    public function handle(OnInviteCreated $event)
    {
        $mailer = new Mailer();

        $invite = $event->invite;
        $by = $invite->byUser;
        $to = Mailer::prepareTo($invite->email);
        $toName = $to['name'];
        $token = $invite->invite_token;


        $sharedArray = array_last($invite->shareable);
        $sharedObject = $sharedArray['shareable_type'];
        $shared = $sharedObject::find($sharedArray['shareable_id']);

        $subject = "hi {$toName}, {$by->name} share with you \"{$shared->name}\" on linku";

        $mailer->sendEmail('invite', $subject, compact('invite', 'shared', 'by', 'toName', 'to', 'token'));
    }
}
