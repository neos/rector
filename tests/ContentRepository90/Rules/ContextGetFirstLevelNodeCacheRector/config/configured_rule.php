<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->rule(\Neos\Rector\ContentRepository90\Rules\ContextGetFirstLevelNodeCacheRector::class);
};
