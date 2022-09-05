<?php

namespace HvacHealth\Contracts;

interface PopALockContract
{
    public static function getLatestOrderCreatedAt(): string;
}
