<?php

namespace HvacHealth\Commands;

use DB;
use HvacHealth\Facades\Health;
use HvacHealth\Monitors\Monitor;
use HvacHealth\Monitors\Result;
use Illuminate\Console\Command;

class RunHealthMonitorsCommand extends Command
{
    public $signature = 'health:monitor';

    public function handle(): int
    {
        $this->info('Running monitors...');

        $results = Health::registeredMonitors()->map(function (Monitor $monitor) {
            return $this->runMonitor($monitor);
        });

        $this->storeResults($results);

        $this->line('');

        $this->info('All done!');

        return 0;
    }

    public function runMonitor(Monitor $monitor)
    {
        try {
            $this->line('');
            $this->line("Running check: {$monitor->getLabel()}...");
            $result = $monitor->run();
        } catch (\Exception $exception) {
            logger($exception);
        }

        return $result;
    }

    protected function storeResults($results): self
    {
        $batch = \Str::uuid();

        $results
            ->each(fn (Result $result) => DB::connection(config('hvac-health.connection'))
            ->table(config('hvac-health.table'))->insert([
                'project' => config('hvac-health.project'),
                'name' => $result->name,
                'meta' => collect($result->meta),
                'status' => $result->status->value,
                'notification_message' => $result->notificationMessage,
                'short_summary' => $result->shortSummary,
                'batch' => $batch,
                'ended_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]));

        return $this;
    }
}
