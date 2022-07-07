<?php

namespace HvacHealth\Events;

class MonitorStateChanged
{
    public $results;

    public function __construct($results)
    {
        $this->results = $results;
    }
}
