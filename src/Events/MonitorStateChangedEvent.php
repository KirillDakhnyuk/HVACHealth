<?php

namespace HvacHealth\Events;

class MonitorStateChangedEvent
{
    public $results;

    public function __construct($results)
    {
        $this->results = $results;
    }
}
