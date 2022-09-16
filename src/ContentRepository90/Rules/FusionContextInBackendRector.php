<?php

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionProcessor;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionContextInBackendRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        // TODO: Implement getRuleDefinition() method.
    }


    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionProcessor::parse($fileContent)
            ->process(function(string $eelExpression): string {

            });
    }
}
