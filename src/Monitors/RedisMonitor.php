<?php

namespace HvacHealth\Monitors;

use Exception;
use HvacHealth\Monitors\Monitor;
use Illuminate\Support\Facades\Redis;

class RedisMonitor extends Monitor
{
    protected string $connectionName = 'default';

    public function connectionName(string $connectionName): self
    {
        $this->connectionName = $connectionName;

        return $this;
    }

    public function run(): Result
    {
        $result = Result::make()->meta([
            'connection_name' => $this->connectionName,
        ]);

        try {
            $response = $this->pingRedis();
        } catch (Exception $exception) {
            return $result->failed("An exception occurred when connecting to Redis: `{$exception->getMessage()}`");
        }

        if ($response === false) {
            return $result->failed("Redis returned a falsy response when try to connection to it.");
        }

        return $result->ok();
    }

    protected function pingRedis()
    {
        return Redis::connection($this->connectionName)->ping();
    }
}
