<?php
declare(strict_types=1);

namespace Neos\Rector;

use Rector\Set\Contract\SetListInterface;

class NeosRectorSets implements SetListInterface
{
    public const CONTENTREPOSITORY_9_0 = __DIR__ . '/../config/set/contentrepository-90.php';
    public const NEOS_8_4 = __DIR__ . '/../config/set/neos-84.php';

    public const ANNOTATIONS_TO_ATTRIBUTES = __DIR__ . '/../config/set/flow-annotations-to-attributes.php';
}
