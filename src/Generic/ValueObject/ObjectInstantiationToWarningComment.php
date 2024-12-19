<?php
declare(strict_types=1);

namespace Neos\Rector\Generic\ValueObject;

class ObjectInstantiationToWarningComment
{
    public function __construct(
        public readonly string $className,
        public readonly string $warningMessage,
    ) {
    }
}
