<?php

namespace HvacHealth\Monitors;

class Result
{
    public Monitor $monitor;
    public Status $status;
    public string $name;
    public string $shortSummary;
    public string $notificationMessage;
    public array $meta = [];

    public static function make(string $message = ''): self
    {
        return new self(Status::ok(), $message);
    }

    public function __construct(
        Status $status,
        string $notificationMessage = '',
        string $shortSummary = '',
        string $name = ''
    ) {
        $this->status = $status;
        $this->notificationMessage = $notificationMessage;
        $this->shortSummary = $shortSummary;
        $this->name = $name;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function shortSummary(string $shortSummary): self
    {
        $this->shortSummary = $shortSummary;

        return $this;
    }

    public function getShortSummary(): string
    {
        if (! empty($this->shortSummary)) {
            return $this->shortSummary;
        }

        return \Str::of($this->status)->snake()->replace('_', ' ')->title();
    }

    public function monitor(Monitor $monitor): self
    {
        $this->monitor = $monitor;

        return $this;
    }

    public function notificationMessage(string $notificationMessage): self
    {
        $this->notificationMessage = $notificationMessage;

        return $this;
    }

    public function getNotificationMessage(): string
    {
        return trans($this->notificationMessage, $this->meta);
    }

    public function ok(string $message = ''): self
    {
        $this->status = Status::ok();

        return $this;
    }

    public function warning(string $message = ''): self
    {
        $this->notificationMessage = $message;

        $this->status = Status::warning();

        return $this;
    }

    public function failed(string $message = ''): self
    {
        $this->notificationMessage = $message;

        $this->status = Status::failed();

        return $this;
    }

    /** @param array<string, mixed> $meta */
    public function meta(array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }
}
