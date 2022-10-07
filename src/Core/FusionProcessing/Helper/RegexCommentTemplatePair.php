<?php
declare(strict_types=1);

namespace Neos\Rector\Core\FusionProcessing\Helper;

class RegexCommentTemplatePair
{
    public function __construct(
        public readonly string $regex,
        public readonly string $template,
    )
    {
    }
}
