<?php

declare(strict_types=1);

namespace threadity\channel\internal;

use Threaded;

final class ThreadedChannelBuffer extends Threaded
{
    public bool $closed = false;

    public function __construct()
    {
        // We remove the value of properties from the queue.
        // Attention! Threads in PHP are shit!
        $this->shift();
    }
}
