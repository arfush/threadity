<?php

declare(strict_types=1);

namespace threadity\channel\exception;

use RuntimeException;

/**
 * ClosedException thrown if some code reads/writes to a closed channel.
 */
class ClosedException extends RuntimeException
{

}
