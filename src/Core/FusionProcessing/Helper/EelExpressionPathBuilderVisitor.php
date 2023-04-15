<?php

namespace Neos\Rector\Core\FusionProcessing\Helper;

use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\EelExpressionValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\ValueAssignment;
use Neos\Rector\Core\FusionProcessing\FusionParser\MergedArrayTree;
use Neos\Rector\Core\FusionProcessing\FusionParser\MergedArrayTreeVisitor;

final class EelExpressionPathBuilderVisitor extends MergedArrayTreeVisitor
{
    public function __construct(private readonly EelExpressionPositions $eelExpressionPositions)
    {
        parent::__construct(
            new MergedArrayTree(),
            fn() => false,
            fn() => [],
        );
    }

    public function visitValueAssignment(ValueAssignment $valueAssignment, array $currentPath = null)
    {
            $currentPath ?? throw new \BadMethodCallException('$currentPath is required.');

        // send currentPath to eel expression value
        $valueAssignment->pathValue->visit($this, $currentPath);
    }

    public function visitEelExpressionValue(EelExpressionValue $eelExpressionValue, array $currentPath = null)
    {
        $eelExpressionPosition = $this->eelExpressionPositions->byEelExpressionValue($eelExpressionValue);
        if ($eelExpressionPosition) {
            $eelExpressionPosition->fusionPath = FusionPath::create($currentPath);
        }
    }
}
