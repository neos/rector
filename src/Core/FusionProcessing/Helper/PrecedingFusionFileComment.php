<?php
declare(strict_types=1);

namespace Neos\Rector\Core\FusionProcessing\Helper;

class PrecedingFusionFileComment
{
    public string $text = '';

    public function __construct(
        public readonly int    $lineNumberOfMatch,
        public readonly string $template
    )
    {
    }
}
