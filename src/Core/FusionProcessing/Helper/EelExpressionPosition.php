<?php

namespace Neos\Rector\Core\FusionProcessing\Helper;

final class EelExpressionPosition
{
    public function __construct(
        public readonly string $eelExpression,
        public readonly int $fromOffset,
        public readonly int $toOffset
    ) {
    }
}
