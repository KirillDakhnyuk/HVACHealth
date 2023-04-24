<?php

namespace HvacHealth\Monitors;

abstract class Monitor
{
    protected ?string $name = null;

    protected ?string $type = null;
    
    protected ?string $label = null;

    abstract public function run();

    public static function new()
    {
        $instance = new static();

        return $instance;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
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

    public function markAsCrashed(): Result
    {
        return new Result(Status::crashed());
    }
}
