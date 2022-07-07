<?php

namespace HvacHealth\Mail;

use Illuminate\Mail\Mailable;

class MonitorStateChanged extends Mailable
{
    protected $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function build()
    {
        return $this
            ->from(config('hvac-health.emails.from'))
            ->subject(config('hvac-health.emails.subject'))
            ->view(config('hvac-health.emails.template'), [
                'project' => config('hvac-health.project'),
                'monitors' => $this->results,
            ]);
    }
}
