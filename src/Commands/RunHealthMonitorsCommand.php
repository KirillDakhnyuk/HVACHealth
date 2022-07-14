<?php

namespace HvacHealth\Commands;

use DB;
use HvacHealth\Events\MonitorStateChangedEvent;
use HvacHealth\Facades\Health;
use HvacHealth\Mail\MonitorStateChanged;
use HvacHealth\Monitors\Monitor;
use HvacHealth\Monitors\Result;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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

        event(new MonitorStateChangedEvent($results));

        if (config('hvac-health.project') && config('hvac-health.emails.template')) {
            $changedMonitors = $results->filter(function ($monitor) {
                if ($monitor->status->value !== $this->getPreviousStatusOf($monitor)) {
                    return [
                        'name' => $monitor->name,
                        'status' => $monitor->status->value,
                    ];
                }
            });


            if ($changedMonitors->isNotEmpty()) {
                $subscribers = DB::connection(config('hvac-health.connection'))
                    ->table('subscribed_to_updates')
                    ->where([
                        'project' => config('hvac-health.project')
                    ])
                    ->get('email');

                $subscribers->each(function ($subscriber) use ($changedMonitors) {
                    Mail::to($subscriber->email)->send(new MonitorStateChanged($changedMonitors));
                });
            }
        }

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
            logger("The monitor named `{$monitor->getName()}` did not complete. An exception was thrown with this message: `".get_class($exception).": {$exception->getMessage()}`");
            $result = $monitor->markAsCrashed();
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
                'branch' => config('hvac-health.branch'),
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

    public function getPreviousStatusOf($monitor)
    {
        $latest = DB::connection('status')->table(config('hvac-health.table'))
            ->where([
                'project' => config('hvac-health.project'),
                'name' => $monitor->name,
            ])
            ->latest()
            ->take(2)
            ->get(['name', 'status']);

        return $latest->last()->status;
    }
}
