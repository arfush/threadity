<?php

declare(strict_types=1);

namespace threadity;

use Closure;
use pocketmine\utils\Utils;

final class Promise
{
    private PromiseShare $share;

    /**
     * @internal By itself, this class is useless!
     */
    public function __construct(PromiseShare $share)
    {
        $this->share = $share;
    }

    public function onCompletion(?Closure $onFulfilled, ?Closure $onRejected, ?Closure $onFinally = null): Promise
    {
        if ($onFulfilled !== null) {
            $this->onFulfilled($onFulfilled);
        }
        if ($onRejected !== null) {
            $this->onRejected($onRejected);
        }
        if ($onFinally !== null) {
            $this->onFinally($onFinally);
        }
        return $this;
    }

    public function onFulfilled(Closure $callback): Promise
    {
        Utils::validateCallableSignature($this->share->signatureOnFulfilled, $callback);
        if ($this->share->state == PromiseShare::FULFILLED) {
            $callback(...$this->share->values);
        } elseif ($this->share->state == PromiseShare::PENDING) {
            $this->share->onFulfilled[] = $callback;
        }
        return $this;
    }

    public function onRejected(Closure $callback): Promise
    {
        Utils::validateCallableSignature($this->share->signatureOnRejected, $callback);
        if ($this->share->state == PromiseShare::REJECTED) {
            $callback(...$this->share->values);
        } elseif ($this->share->state == PromiseShare::PENDING) {
            $this->share->onRejected[] = $callback;
        }
        return $this;
    }

    public function onFinally(Closure $callback): Promise
    {
        Utils::validateCallableSignature(function (): void {}, $callback);
        if ($this->share->state != PromiseShare::PENDING) {
            $callback();
        } else {
            $this->share->onFinally[] = $callback;
        }
        return $this;
    }
}
