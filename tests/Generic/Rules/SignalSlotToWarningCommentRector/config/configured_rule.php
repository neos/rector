<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\SignalSlotToWarningCommentRector;
use Neos\Rector\Generic\ValueObject\SignalSlotToWarningComment;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->ruleWithConfiguration(SignalSlotToWarningCommentRector::class, [
        new SignalSlotToWarningComment(Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub::class, 'beforeMove', 'Signal "beforeMove" doesn\'t exist anymore'),
        new SignalSlotToWarningComment(Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub::class, 'afterMove', 'Signal "afterMove" doesn\'t exist anymore'),
    ]);
};
