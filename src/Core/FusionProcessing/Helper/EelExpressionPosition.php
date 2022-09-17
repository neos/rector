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

    public function withOffset(int $offset): self
    {
        return new self(
            $this->eelExpression,
            $this->fromOffset + $offset,
            $this->toOffset + $offset
        );
    }

    public function withEelExpression(string $eelExpression)
    {
        return new self(
            $eelExpression,
            $this->fromOffset,
            $this->toOffset
        );
    }
}
