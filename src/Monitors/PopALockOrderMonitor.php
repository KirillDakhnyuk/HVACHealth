<?php

namespace HvacHealth\Monitors;

use HvacHealth\Facades\PopALock;
use Illuminate\Support\Facades\Date;

class PopALockOrderMonitor extends Monitor
{
    public $name = 'Pop-A-Lock order';

    public function run(): Result
    {
        $result = Result::make();

        $latest = Date::parse(PopALock::getLatestOrderCreatedAt());

        if ($latest < now()->subHours(6)) {
            return $result->failed(trans('hvac-health::pop-a-lock-order.red'));
        }

        if ($latest < now()->subHour()) {
            return $result->warning(trans('hvac-health::pop-a-lock-order.yellow'));
        }

        return $result->ok(trans('hvac-health::pop-a-lock-order.green'));
    }
}
