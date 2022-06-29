<?php

namespace HVACHealth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HVACHealth\Health
 */

class Health extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'health';
    }
}
