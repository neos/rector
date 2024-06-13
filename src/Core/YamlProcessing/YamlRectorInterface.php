<?php

declare(strict_types=1);

namespace Neos\Rector\Core\YamlProcessing;

use Rector\Contract\Rector\RectorInterface;

interface YamlRectorInterface extends RectorInterface
{
    public function refactorFileContent(string $fileContent): string;
}
