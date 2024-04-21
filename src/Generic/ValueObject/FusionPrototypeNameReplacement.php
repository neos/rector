<?php
declare(strict_types=1);

namespace Neos\Rector\Generic\ValueObject;

class FusionPrototypeNameReplacement
{
    public function __construct(
        public readonly string $oldName,
        public readonly string $newName,
        public readonly ?string $comment = null
    ) {
    }
}
