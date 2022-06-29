<?php

namespace HvacHealth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HvacHealth\Health
 */

class Health extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'health';
    }
}
