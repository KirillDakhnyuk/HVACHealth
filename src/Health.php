<?php

namespace HvacHealth;

class Health
{
    protected array $monitors = [];

    public function monitors(array $monitors)
    {
        $this->monitors = array_merge($this->monitors, $monitors);

        return $this;
    }

    public function registeredMonitors()
    {
        return collect($this->monitors);
    }

    public function subscribers(array $subscribers)
    {
        $this->subscribers = $subscribers;

        return $this;
    }

    public function registeredSubscribers()
    {
        return collect($this->subscribers);
    }
}
