<?php

declare(strict_types=1);

namespace Neos\Rector\Generic\ValueObject;

class FusionFlowQueryNodePropertyToWarningComment
{
    public function __construct(
        public readonly string $property,
        public readonly string $warningMessage,
    )
    {
    }


}
