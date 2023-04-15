<?php

namespace Neos\Rector\Core\FusionProcessing\Helper;

use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\EelExpressionValue;

final class EelExpressionPosition
{
    /**
     * the fusion path leading to this eel expression. Not always filled (e.g. not in AFX).
     */
    public FusionPath $fusionPath;

    public function __construct(
        public readonly string $eelExpression,
        public readonly int $fromOffset,
        public readonly int $toOffset,
        public readonly ?EelExpressionValue $eelExpressionValue = null
    ) {
        $this->fusionPath = FusionPath::createEmpty();
    }

    public function withOffset(int $offset): self
    {
        return new self(
            $this->eelExpression,
            $this->fromOffset + $offset,
            $this->toOffset + $offset,
            $this->eelExpressionValue
        );
    }

    public function withEelExpression(string $eelExpression)
    {
        return new self(
            $eelExpression,
            $this->fromOffset,
            $this->toOffset,
            $this->eelExpressionValue
        );
    }
}
