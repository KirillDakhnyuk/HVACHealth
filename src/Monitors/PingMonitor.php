<?php

namespace HvacHealth\Monitors;

use Exception;
use Http;
use HvacHealth\Exceptions\InvalidMonitor;
use HvacHealth\Monitors\Monitor;
use HvacHealth\Monitors\Result;

class PingMonitor extends Monitor
{
    public ?string $name = 'Power Status';
    public ?string $type = 'ping';
    public ?string $url = null;
    public ?string $failureMessage = null;
    public int $timeout = 1;
    public string $method = 'GET';
    public array $headers = [];

    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function timeout(int $seconds): self
    {
        $this->timeout = $seconds;

        return $this;
    }

    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function headers(array $headers = []): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function failureMessage(string $failureMessage): self
    {
        $this->failureMessage = $failureMessage;

        return $this;
    }

    public function run(): Result
    {
        if (is_null($this->url)) {
            throw InvalidMonitor::urlNotSet();
        }

        try {
            if (! Http::timeout($this->timeout)->withHeaders($this->headers)->send($this->method, $this->url)->successful()) {
                return $this->failedResult();
            }
        } catch (Exception $e) {
            return $this->failedResult();
        }

        return Result::make()
            ->name($this->name)
            ->type($this->type)
            ->ok()
            ->shortSummary('Server(s) are accessible.');
    }

    protected function failedResult(): Result
    {
        return Result::make()
            ->name($this->name)
            ->failed()
            ->shortSummary('Server(s) are unreachable.')
            ->notificationMessage($this->failureMessage ?? "A server could not be reached.");
    }
}
