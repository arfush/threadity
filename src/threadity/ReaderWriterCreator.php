<?php

declare(strict_types=1);

namespace threadity;

use Threaded;
use JetBrains\PhpStorm\ArrayShape;

final class ReaderWriterCreator
{
    #[ArrayShape([Reader::class, Writer::class])]
    public static function create(): array
    {
        $buffer = new Threaded();
        return [new Reader($buffer), new Writer($buffer)];
    }

    private function __construct() {}
}
