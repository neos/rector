<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
use Neos\Rector\Generic\Rules\MethodCallToWarningCommentRector;
use Neos\Rector\Generic\ValueObject\MethodCallToWarningComment;
use Rector\Config\RectorConfig;
    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withConfiguredRule(MethodCallToWarningCommentRector::class, [
        new MethodCallToWarningComment(NodeLegacyStub::class, 'getWorkspace', '!! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.'),
        new MethodCallToWarningComment(NodeLegacyStub::class, 'getNode', '!! %%1$s::%%2$s() has been removed.'),
    ]);
return $rectorConfig;
