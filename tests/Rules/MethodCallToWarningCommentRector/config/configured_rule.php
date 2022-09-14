<?php

declare (strict_types=1);

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Rector\Generic\Rules\MethodCallToWarningCommentRector;
use Neos\Rector\Generic\ValueObject\MethodCallToWarningComment;
use Rector\Config\RectorConfig;
return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->ruleWithConfiguration(MethodCallToWarningCommentRector::class, [
        new MethodCallToWarningComment(Node::class, 'getWorkspace', '!! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.')
    ]);
};
