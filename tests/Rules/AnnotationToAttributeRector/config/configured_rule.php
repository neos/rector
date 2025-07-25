<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        \Neos\Rector\NeosRectorSets::ANNOTATIONS_TO_ATTRIBUTES
    ]);
};
