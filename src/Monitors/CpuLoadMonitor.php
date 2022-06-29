<?php

namespace HvacHealth\Monitors;

class CpuLoadMonitor extends Monitor
{
    public function run()
    {
        $result = sys_getloadavg();

        return $result;
    }
}
