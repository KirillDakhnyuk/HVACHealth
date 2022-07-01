<?php

namespace HvacHealth\Commands;

use HvacHealth\Facades\Health;
use HvacHealth\Monitors\Monitor;
use HvacHealth\Monitors\ScheduleMonitor;
use Illuminate\Console\Command;

class ScheduleHeartbeatMonitorCommand extends Command
{
    protected $signature = 'health:schedule-monitor-heartbeat';

    public function handle(): int
    {
        $scheduleMonitor = Health::registeredMonitors()->first(
            fn (Monitor $monitor) => $monitor instanceof ScheduleMonitor
        );

        if (! $scheduleMonitor) {
            $this->error("In order to use this command, you should register the `HvacHealth\Monitors\ScheduleMonitor`");

            return static::FAILURE;
        }

        $cacheKey = $scheduleMonitor->getCacheKey();

        if (! $cacheKey) {
            $this->error(
                "You must set the `cacheKey` of `HvacHealth\Monitors\Monitors\ScheduleMonitor` to a non-empty value"
            );

            return static::FAILURE;
        }

        cache()->store($scheduleMonitor->getCacheStoreName())->set($cacheKey, now()->timestamp);

        return static::SUCCESS;
    }
}
