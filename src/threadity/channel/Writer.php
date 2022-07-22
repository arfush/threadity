<?php

declare(strict_types=1);

namespace threadity\channel;

final class Writer
{
    private Channel $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * This method simply delegates to {@link Channel::write()}
     */
    public function write(string|int|float|bool $data): void
    {
        $this->channel->write($data);
    }
}
