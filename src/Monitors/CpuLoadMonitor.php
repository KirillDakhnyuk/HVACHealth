<?php

namespace HvacHealth\Monitors;

class CpuLoadMonitor extends Monitor
{
    public ?string $name = 'Server Processing';
    public ?string $type = 'ping';
    protected ?float $failWhenLoadIsHigherInTheLastMinute = null;
    protected ?float $failWhenLoadIsHigherInTheLast5Minutes = null;
    protected ?float $failWhenLoadIsHigherInTheLast15Minutes = null;

    public function failWhenLoadIsHigherInTheLastMinute(float $load): self
    {
        $this->failWhenLoadIsHigherInTheLastMinute = $load;

        return $this;
    }

    public function failWhenLoadIsHigherInTheLast5Minutes(float $load): self
    {
        $this->failWhenLoadIsHigherInTheLast5Minutes = $load;

        return $this;
    }

    public function failWhenLoadIsHigherInTheLast15Minutes(float $load): self
    {
        $this->failWhenLoadIsHigherInTheLast15Minutes = $load;

        return $this;
    }

    public function run()
    {
        $cpuLoad = $this->measure();

        $result = Result::make()
            ->ok('Operating normally')
            ->name($this->name)
            ->type($this->type)
            ->shortSummary(
                "{$cpuLoad['lastMinute']} {$cpuLoad['last5Minutes']} {$cpuLoad['last15Minutes']}"
            )
            ->meta([
                'last_minute' => $cpuLoad['lastMinute'],
                'last_5_minutes' => $cpuLoad['last5Minutes'],
                'last_15_minutes' => $cpuLoad['last15Minutes'],
            ]);

        if ($this->failWhenLoadIsHigherInTheLastMinute) {
            if ($cpuLoad['lastMinute'] > ($this->failWhenLoadIsHigherInTheLastMinute)) {
                return $result->failed("Server load over the last minute is {$cpuLoad['lastMinute']} which is higher than the allowed {$this->failWhenLoadIsHigherInTheLastMinute}");
            }
        }

        if ($this->failWhenLoadIsHigherInTheLast5Minutes) {
            if ($cpuLoad['last5Minutes'] > ($this->failWhenLoadIsHigherInTheLast5Minutes)) {
                return $result->failed("Server load of the last 5 minutes is {$cpuLoad['last5Minutes']} which is higher than the allowed {$this->failWhenLoadIsHigherInTheLast5Minutes}");
            }
        }

        if ($this->failWhenLoadIsHigherInTheLast15Minutes) {
            if ($cpuLoad['last15Minutes'] > ($this->failWhenLoadIsHigherInTheLast15Minutes)) {
                return $result->failed("Server load of the last 15 minutes is {$cpuLoad['last15Minutes']} which is higher than the allowed {$this->failWhenLoadIsHigherInTheLast15Minutes}");
            }
        }

        return $result;
    }

    public function measure()
    {
        $load = sys_getloadavg();

        return collect([
            'lastMinute' => $load[0],
            'last5Minutes' => $load[1],
            'last15Minutes' => $load[2]
        ]);
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
