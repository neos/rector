<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\SignalSlotToWarningCommentRector;
use Neos\Rector\Generic\ValueObject\SignalSlotToWarningComment;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withConfiguredRule(SignalSlotToWarningCommentRector::class, [
        new SignalSlotToWarningComment(Neos\ContentRepository\Domain\Model\Node::class, 'beforeMove', 'Signal "beforeMove" doesn\'t exist anymore'),
        new SignalSlotToWarningComment(Neos\ContentRepository\Domain\Model\Node::class, 'afterMove', 'Signal "afterMove" doesn\'t exist anymore'),
    ]);
return $rectorConfig;
