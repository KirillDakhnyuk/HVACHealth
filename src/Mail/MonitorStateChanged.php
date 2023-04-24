<?php

namespace HvacHealth\Mail;

use Illuminate\Mail\Mailable;

class MonitorStateChanged extends Mailable
{

    public function __construct(protected $results, protected $unsubscribeLink)
    {
        //
    }

    public function build()
    {
        return $this
            ->from(config('hvac-health.emails.from'))
            ->subject(config('hvac-health.emails.subject'))
            ->view(config('hvac-health.emails.template'), [
                'project' => config('hvac-health.project'),
                'monitors' => $this->results,
                'unsubscribeLink' => $this->unsubscribeLink
            ]);
    }
}
