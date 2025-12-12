<?php

declare(strict_types=1);

namespace Neos\Rector\Generic\ValueObject;

class FusionNodePropertyPathToWarningComment
{
    public function __construct(
        public readonly string $propertyPath,
        public readonly string $warningMessage,
    ) {
    }
}
