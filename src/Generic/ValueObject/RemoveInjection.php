<?php
declare(strict_types=1);

namespace Neos\Rector\Generic\ValueObject;

class RemoveInjection
{
    public function __construct(
        public readonly string $objectType,
    )
    {
    }
}
