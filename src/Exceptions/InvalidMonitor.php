<?php

namespace HvacHealth\Exceptions;

use Exception;
use HvacHealth\Monitors\Monitor;

class InvalidMonitor extends Exception
{
    public static function doesNotExtendMonitor(mixed $invalidValue): self
    {
        $monitorClass = Monitor::class;

        $extraMessage = '';

        if (is_string($invalidValue)) {
            $extraMessage = " You tried to register a string `{$invalidValue}`";
        }

        if (is_object($invalidValue)) {
            $invalidClass = get_class($invalidValue);

            $extraMessage = " You tried to register class `{$invalidClass}`";
        }

        return new self(
            "You tried to register an invalid monitor. A valid monitor should extend `$monitorClass`.{$extraMessage}"
        );
    }

    public static function urlNotSet(): self
    {
        return new self('When using the `PingMonitor` you must call `url` to pass the URL you want to ping.');
    }
}