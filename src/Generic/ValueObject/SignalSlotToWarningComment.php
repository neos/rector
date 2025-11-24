<?php
declare(strict_types=1);

namespace Neos\Rector\Generic\ValueObject;

class SignalSlotToWarningComment
{
    public function __construct(
        public readonly string $className,
        public readonly string $signalName,
        public readonly string $warningMessage,
    ) {
    }
}
