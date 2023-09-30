<?php

declare (strict_types=1);
//namespace RectorPrefix202208;

use Neos\Rector\ContentRepository90\Rules\ContextGetRootNodeRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->rule(ContextGetRootNodeRector::class);
    $rectorConfig->rule(\Neos\Rector\Generic\Rules\RemoveDuplicateCommentRector::class);
};
