<?php
declare(strict_types=1);

namespace Neos\Rector\ValueObject;

class MethodCallToWarningComment
{
    public function __construct(
        public readonly string $objectType,
        public readonly string $methodName,
        public readonly string $warningMessage,
    )
    {
    }
}
