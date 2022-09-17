<?php

namespace Neos\Rector\Core\FusionProcessing\Helper;

final class AfxExpressionPosition
{
    public function __construct(
        public readonly string $code,
        public readonly int $fromOffset,
        public readonly int $toOffset
    ) {
    }
}
