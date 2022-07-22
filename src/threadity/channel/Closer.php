<?php

declare(strict_types=1);

namespace threadity\channel;

final class Closer
{
    private Channel $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * This method simply delegates to {@link Channel::close()}
     */
    public function close(): void
    {
        $this->channel->close();
    }
}
