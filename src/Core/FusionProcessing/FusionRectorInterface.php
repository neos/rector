<?php

declare(strict_types=1);

namespace Neos\Rector\Core\FusionProcessing;

use Rector\Contract\Rector\RectorInterface;

interface FusionRectorInterface extends RectorInterface
{
    public function refactorFileContent(string $fileContent): string;
}
