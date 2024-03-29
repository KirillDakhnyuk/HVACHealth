<?php

namespace HvacHealth\Monitors;

use HvacHealth\Monitors\Monitor;
use HvacHealth\Monitors\Result;
use Illuminate\Support\Facades\Date;
use Twilio\Rest\Client;

class TwilioMonitor extends Monitor
{
    public ?string $name = 'Messaging';
    public ?string $type = 'twilio';
    protected $sid;
    protected $token;
    protected $twilio;
    protected $interval = 'last hour';
    protected $undeliveredMax = 5;

    public function run(): Result
    {
        $result = Result::make()->name($this->name)->type($this->type);

        if (! $this->sid && ! $this->token) {
            return $result->failed('Credentials are required.');
        }

        $this->twilio = new Client($this->sid, $this->token);

        $response = $this->twilio->messages->page([
            'from' => $this->from,
            'dateSentAfter' => Date::parse($this->interval, 'UTC')->toDateTimeString()
        ]);

        $undelivered = collect($response)->filter(fn ($message) => $message->status === 'undelivered');

        if ($undelivered->count() >= $this->undeliveredMax) {
            return $result->failed(trans('hvac-health::twilio.red', [
                'count' => $undelivered->count(),
                'interval' => $this->interval
            ]));
        }

        return $result->ok(trans('hvac-health::twilio.green', ['interval' => $this->interval]));
    }

    public function sid($sid)
    {
        $this->sid = $sid;

        return $this;
    }

    public function token($token)
    {
        $this->token = $token;

        return $this;
    }

    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    public function interval($interval)
    {
        $this->interval = $interval;

        return $this;
    }

    public function undeliveredMax($undeliveredMax)
    {
        $this->undeliveredMax = $undeliveredMax;

        return $this;
    }
}
