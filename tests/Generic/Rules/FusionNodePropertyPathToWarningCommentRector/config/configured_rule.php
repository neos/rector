<?php

declare (strict_types=1);

use Neos\Rector\Core\FusionProcessing\FusionFileProcessor;
use Neos\Rector\Generic\Rules\FusionNodePropertyPathToWarningCommentRector;
use Neos\Rector\Generic\ValueObject\FusionNodePropertyPathToWarningComment;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) : void {
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();
    $services->set(FusionFileProcessor::class);
    $rectorConfig->disableParallel(); // does not work for fusion files - see https://github.com/rectorphp/rector-src/pull/2597#issuecomment-1190120688

    $rectorConfig->ruleWithConfiguration(FusionNodePropertyPathToWarningCommentRector::class, [
        new FusionNodePropertyPathToWarningComment('removed', 'Line %LINE: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.'),
        new FusionNodePropertyPathToWarningComment('hiddenBeforeDateTime', 'Line %LINE: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.'),
        new FusionNodePropertyPathToWarningComment('hiddenAfterDateTime', 'Line %LINE: !! node.hiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.'),
        new FusionNodePropertyPathToWarningComment('foo.bar', 'Line %LINE: !! node.foo.bar is not supported anymore.'),
    ]);
};
