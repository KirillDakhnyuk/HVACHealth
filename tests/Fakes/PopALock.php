<?php

namespace HvacHealth\Tests\Fakes;

class PopALock implements \HvacHealth\Contracts\PopALockContract
{

    public static function getLatestOrderCreatedAt(): string
    {
        return now()->toDateTimeString();
    }
}
