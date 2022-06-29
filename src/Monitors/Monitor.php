<?php

namespace HVACHealth\Monitors;

use Illuminate\Console\Scheduling\ManagesFrequencies;

abstract class Monitor
{
    use ManagesFrequencies;

    protected string $expression = '* * * * *';

    protected string $label = 'test';

    abstract public function run();

    public static function new()
    {
        $instance = new static();

        $instance->everyMinute();

        return $instance;
    }

    public function getLabel()
    {
        return $this->label;
    }
}
