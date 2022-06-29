<?php

namespace HvacHealth\Monitors;

use Exception;
use HvacHealth\Monitors\Monitor;
use HvacHealth\Monitors\Result;
use Illuminate\Support\Facades\DB;

class DatabaseMonitor extends Monitor
{
    protected ?string $connectionName = null;

    public function connectionName(string $connectionName): self
    {
        $this->connectionName = $connectionName;

        return $this;
    }

    public function run(): Result
    {
        $connectionName = $this->connectionName ?? $this->getDefaultConnectionName();

        $result = Result::make()
            ->name('Database')
            ->meta([
                'connection_name' => $connectionName,
            ]);

        try {
            DB::connection($connectionName)->getPdo();

            return $result->ok();
        } catch (Exception $exception) {
            return $result->failed("Could not connect to the database: `{$exception->getMessage()}`");
        }
    }

    protected function getDefaultConnectionName(): string
    {
        return config('database.default');
    }
}
