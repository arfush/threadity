<?php

declare(strict_types=1);

namespace threadity;

use Threaded;

class Reader
{
    protected Threaded $buffer;

    public function __construct(Threaded $buffer)
    {
        $this->buffer = $buffer;
    }

    public function read(bool $wait = true): string|int|float|bool|null
    {
        return $this->buffer->synchronized(function () use ($wait): string|int|float|bool|null {
            if ($wait && $this->buffer->count() == 0) {
                $this->buffer->wait();
            }
            return $this->buffer->shift();
        });
    }
}
