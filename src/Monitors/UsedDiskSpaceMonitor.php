<?php

namespace HvacHealth\Monitors;

use HvacHealth\Monitors\Monitor;
use HvacHealth\Monitors\Result;
use Spatie\Regex\Regex;
use Symfony\Component\Process\Process;

class UsedDiskSpaceMonitor extends Monitor
{
    protected int $warningThreshold = 70;
    protected int $errorThreshold = 90;

    public function warnWhenUsedSpaceIsAbovePercentage(int $percentage): self
    {
        $this->warningThreshold = $percentage;

        return $this;
    }

    public function failWhenUsedSpaceIsAbovePercentage(int $percentage): self
    {
        $this->errorThreshold = $percentage;

        return $this;
    }

    public function run(): Result
    {
        $diskSpaceUsedPercentage = $this->getDiskUsagePercentage();

        $result = Result::make()
            ->name('Used disk space')
            ->meta(['disk_space_used_percentage' => $diskSpaceUsedPercentage])
            ->shortSummary($diskSpaceUsedPercentage . '%');

        if ($diskSpaceUsedPercentage > $this->errorThreshold) {
            return $result->failed("The disk is almost full ({$diskSpaceUsedPercentage}% used).");
        }

        if ($diskSpaceUsedPercentage > $this->warningThreshold) {
            return $result->warning("The disk is almost full ({$diskSpaceUsedPercentage}% used).");
        }

        return $result->ok();
    }

    protected function getDiskUsagePercentage(): int
    {
        $process = Process::fromShellCommandline('df -P .');

        $process->run();

        $output = $process->getOutput();

        return (int) Regex::match('/(\d*)%/', $output)->group(1);
    }
}
