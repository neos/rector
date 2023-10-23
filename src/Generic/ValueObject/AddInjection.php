<?php
declare(strict_types=1);

namespace Neos\Rector\Generic\ValueObject;

class AddInjection
{
    public function __construct(
        public readonly string $memberName,
        public readonly string $objectType,
    ) {
    }
}
