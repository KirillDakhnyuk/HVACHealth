<?php

namespace HvacHealth\Monitors;

use HvacHealth\Facades\PopALock;
use Illuminate\Support\Facades\Date;

class PopALockOrderMonitor extends Monitor
{
    public function run(): Result
    {
        $result = Result::make();

        $latest = Date::parse(PopALock::getLatestOrderCreatedAt());

        if ($latest < now()->subHours(6)) {
            return $result->failed(trans('pop-a-lock-order.red'));
        }

        if ($latest < now()->subHour()) {
            return $result->warning(trans('pop-a-lock-order.yellow'));
        }

        return $result->ok(trans('pop-a-lock-order.green'));
    }
}
