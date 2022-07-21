<?php

declare(strict_types=1);

namespace threadity;

use Threaded;

class Writer
{
    protected Threaded $buffer;

    public function __construct(Threaded $buffer)
    {
        $this->buffer = $buffer;
    }

    public function write(string|int|float|bool $data): void
    {
        $this->buffer[] = $data;
        $this->buffer->notify();
    }
}
