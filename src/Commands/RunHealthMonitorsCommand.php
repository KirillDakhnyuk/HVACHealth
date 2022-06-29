<?php

namespace HVACHealth\Commands;

use DB;
use HVACHealth\Facades\Health;
use HVACHealth\Monitors\Monitor;
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
            report($exception);
        }

        return $result;
    }

    protected function storeResults($results): self
    {
        $results->each(fn () => DB::connection('status')->table('health_check_result_history_items')->insert([
            'check_name' => 'test'
        ]));

        return $this;
    }
}
