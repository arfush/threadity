<?php

declare(strict_types=1);

namespace threadity\channel;

use LogicException;
use threadity\channel\exception\NoDataException;
use threadity\channel\exception\ClosedException;
use threadity\channel\internal\ThreadedChannelBuffer;

final class Channel
{
    private ThreadedChannelBuffer $buffer;

    private ?Reader $reader = null;
    private ?Writer $writer = null;
    private ?Closer $closer = null;

    public function __construct()
    {
        $this->buffer = new ThreadedChannelBuffer();
    }

    /**
     * Writes data to the channel and notifies a reader.
     *
     * @throws ClosedException
     */
    public function write(string|int|float|bool $data): void
    {
        $this->testClosed();
        $this->buffer[] = $data;
        $this->buffer->notifyOne();
    }

    /**
     * Reads data from a channel. If there is no data in the channel and $wait is TRUE,
     * then block the thread until it reads data or the channel closes. Otherwise, the NoDataException will be thrown.
     *
     * @throws NoDataException
     * @throws ClosedException
     *
     * @param bool $wait
     *
     * @return string|int|float|bool
     */
    public function read(bool $wait = true): string|int|float|bool
    {
        $this->testClosed();
        return $this->buffer->synchronized(function () use ($wait): string|int|float|bool {
            if ($this->buffer->count() == 0) {
                if ($wait) {
                    $this->buffer->wait();
                    $this->testClosed();
                } else {
                    throw new NoDataException();
                }
            }
            return $this->buffer->shift();
        });
    }

    public function close(): void
    {
        if ($this->buffer->closed) {
            throw new LogicException("Already closed");
        }
        $this->buffer->closed = true;
        $this->buffer->notify();
    }

    public function getReader(): Reader
    {
        $this->testClosed();
        if ($this->reader === null) {
            $this->reader = new Reader($this);
        }
        return $this->reader;
    }

    public function getWriter(): Writer
    {
        $this->testClosed();
        if ($this->writer === null) {
            $this->writer = new Writer($this);
        }
        return $this->writer;
    }

    public function getCloser(): Closer
    {
        $this->testClosed();
        if ($this->closer === null) {
            $this->closer = new Closer($this);
        }
        return $this->closer;
    }

    private function testClosed(): void
    {
        if ($this->buffer->closed) {
            throw new ClosedException();
        }
    }
}
