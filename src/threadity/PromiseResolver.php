<?php

declare(strict_types=1);

namespace threadity;

use Closure;
use LogicException;

final class PromiseResolver
{
    private PromiseShare $share;
    private Promise $promise;

    public function __construct(Closure $signatureOnFulfilled, Closure $signatureOnRejected)
    {
        $this->share = new PromiseShare($signatureOnFulfilled, $signatureOnRejected);
        $this->promise = new Promise($this->share);
    }

    public function fulfill(mixed...$values): void
    {
        if ($this->share->state != PromiseShare::PENDING) {
            throw new LogicException("Promise has already been fulfilled/rejected");
        }
        foreach ($this->share->onFulfilled as $callback) {
            $callback(...$values);
        }
        foreach ($this->share->onFinally as $callback) {
            $callback();
        }
        $this->share->state = PromiseShare::FULFILLED;
        $this->share->values = $values;
    }

    public function reject(mixed...$values): void
    {
        if ($this->share->state != PromiseShare::PENDING) {
            throw new LogicException("Promise has already been fulfilled/rejected");
        }
        foreach ($this->share->onRejected as $callback) {
            $callback(...$values);
        }
        foreach ($this->share->onFinally as $callback) {
            $callback();
        }
        $this->share->state = PromiseShare::REJECTED;
        $this->share->values = $values;
    }

    public function getPromise(): Promise
    {
        return $this->promise;
    }
}
