<?php

namespace HvacHealth\Monitors;

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

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getName(): string
    {
        if ($this->name) {
            return $this->name;
        }

        $baseName = class_basename(static::class);

        return \Str::of($baseName)->beforeLast('Check');
    }
}
