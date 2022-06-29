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
            dd($exception);
        }

        return $result;
    }

    protected function storeResults($results): self
    {
        $results
            ->each(fn (Result $result) => DB::connection('status')->table('health_check_result_history_items')->insert([
                'check_name' => $result->name,
                'meta' => collect($result->meta),
                'status' => $result->status->value,
                'notification_message' => $result->notificationMessage,
                'short_summary' => $result->shortSummary,
                'ended_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]));

        return $this;
    }
}
