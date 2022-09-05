<?php

namespace HvacHealth\Facades;

use HvacHealth\Contracts\PopALockContract;
use Illuminate\Support\Facades\Facade;

/**
 * @see \HvacHealth\PopALock
 * @mixin PopALockContract
 */

class PopALock extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PopALockContract::class;
    }
}
