<?php

declare(strict_types=1);

namespace threadity\channel\exception;

use RuntimeException;
use threadity\channel\Channel;

/**
 * NoDataException thrown if there is no data in a channel during a non-blocking {@link Channel::read()} call.
 */
class NoDataException extends RuntimeException
{

}
