<?php

declare(strict_types=1);

namespace Crell\Serde\Extractor;

use Crell\Serde\JsonFormatter;

interface Injector
{
    public function getValue(JsonFormatter $formatter, string $format, mixed $source, string $name, string $type): mixed;

    public function supportsInject(string $type, string $format): bool;
}
