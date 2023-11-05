<?php

declare (strict_types=1);

use Neos\Rector\Core\FusionProcessing\FusionFileProcessor;
use Rector\Config\RectorConfig;
use Neos\Rector\Generic\ValueObject\FusionFlowQueryNodePropertyToWarningComment;
use Neos\Rector\Generic\Rules\FusionFlowQueryNodePropertyToWarningCommentRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();
    $services->set(FusionFileProcessor::class);
    $rectorConfig->disableParallel(); // does not work for fusion files - see https://github.com/rectorphp/rector-src/pull/2597#issuecomment-1190120688

    $rectorConfig->ruleWithConfiguration(FusionFlowQueryNodePropertyToWarningCommentRector::class, [
        new FusionFlowQueryNodePropertyToWarningComment('_autoCreated', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_autoCreated")" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'),
        new FusionFlowQueryNodePropertyToWarningComment('_contextPath', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_contextPath")" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'),
    ]);
};
