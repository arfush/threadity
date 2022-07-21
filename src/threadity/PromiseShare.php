<?php

declare(strict_types=1);

namespace threadity;

use Closure;

/**
 * @internal
 */
final class PromiseShare
{
    public const PENDING = 0;
    public const FULFILLED = 1;
    public const REJECTED = 2;

    /** @var Closure[] */
    public array $onFulfilled = [];
    public Closure $signatureOnFulfilled;

    /** @var Closure[] */
    public array $onRejected = [];
    public Closure $signatureOnRejected;

    /** @var Closure[] */
    public array $onFinally = [];

    public int $state = PromiseShare::PENDING;
    public array $values = [];

    public function __construct(Closure $signatureOnFulfilled, Closure $signatureOnRejected)
    {
        $this->signatureOnFulfilled = $signatureOnFulfilled;
        $this->signatureOnRejected = $signatureOnRejected;
    }
}
