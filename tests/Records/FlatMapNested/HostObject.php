<?php

declare(strict_types=1);

namespace Crell\Serde\Records\FlatMapNested;

use Crell\Serde\ClassNameTypeMap;
use Crell\Serde\Field;
use Crell\Serde\SequenceField;

class HostObject
{
    public function __construct(
        #[Field(flatten: true)]
        public Nested $nested,
        #[SequenceField(arrayType: Item::class)]
        public array $list,
    ) {}
}
