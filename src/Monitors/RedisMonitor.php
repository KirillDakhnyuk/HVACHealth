<?php

namespace HvacHealth\Monitors;

use Exception;
use HvacHealth\Monitors\Monitor;
use Illuminate\Support\Facades\Redis;

class RedisMonitor extends Monitor
{
    public ?string $name = 'Background Processes';
    public ?string $type = 'queue';
    protected string $connectionName = 'default';

    public function connectionName(string $connectionName): self
    {
        $this->connectionName = $connectionName;

        return $this;
    }

    public function run(): Result
    {
        $result = Result::make()
            ->name($this->name)
            ->type($this->type)
            ->meta([
                'connection_name' => $this->connectionName,
            ]);

        try {
            $response = $this->pingRedis();
        } catch (Exception $exception) {
            return $result->failed("An exception occurred when connecting to the queue processor: `{$exception->getMessage()}`");
        }

        if ($response === false) {
            return $result->failed("The queue could not be reached.");
        }

        return $result->ok();
    }

    protected function pingRedis()
    {
        return Redis::connection($this->connectionName)->ping();
    }
}
