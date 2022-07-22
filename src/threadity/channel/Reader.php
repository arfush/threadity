<?php

declare(strict_types=1);

namespace threadity\channel;

final class Reader
{
    private Channel $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * This method simply delegates to {@link Channel::read()}
     */
    public function read(bool $wait = true): string|int|float|bool
    {
        return $this->channel->read($wait);
    }
}
