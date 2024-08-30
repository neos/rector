<?php
declare (strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\MethodCall\MethodCallToPropertyFetchRector;
use Neos\Rector\Generic\Rules\MethodCallToWarningCommentRector;
use Neos\Rector\Generic\Rules\FusionNodePropertyPathToWarningCommentRector;
use Rector\Transform\ValueObject\MethodCallToPropertyFetch;
use Neos\Rector\Generic\ValueObject\MethodCallToWarningComment;

return static function (RectorConfig $rectorConfig): void {

    // Register FusionFileProcessor. All Fusion Rectors will be auto-registered at this processor.
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();
    $services->set(\Neos\Rector\Core\FusionProcessing\FusionFileProcessor::class);
    $services->set(\Neos\Rector\Core\YamlProcessing\YamlFileProcessor::class);
    $rectorConfig->disableParallel(); // parallel does not work for non-PHP-Files, so we need to disable it - see https://github.com/rectorphp/rector-src/pull/2597#issuecomment-1190120688

    /** @var MethodCallToPropertyFetch[] $methodCallToPropertyFetches */
    $methodCallToPropertyFetches = [];

    /** @var MethodCallToWarningComment[] $methodCallToWarningComments */
    $methodCallToWarningComments = [];


    $fusionFlowQueryPropertyToComments = [];
    $fusionNodePropertyPathToWarningComments = [];


    /**
     * Put your rules below here
     */





    /**
     * CLEAN UP / END GLOBAL RULES
     */
    $rectorConfig->ruleWithConfiguration(FusionNodePropertyPathToWarningCommentRector::class, $fusionFlowQueryPropertyToComments);
    $rectorConfig->ruleWithConfiguration(MethodCallToPropertyFetchRector::class, $methodCallToPropertyFetches);
    $rectorConfig->ruleWithConfiguration(MethodCallToWarningCommentRector::class, $methodCallToWarningComments);
    $rectorConfig->ruleWithConfiguration(FusionNodePropertyPathToWarningCommentRector::class, $fusionNodePropertyPathToWarningComments);

};
