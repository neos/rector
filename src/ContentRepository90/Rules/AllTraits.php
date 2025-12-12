<?php
declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\ContentRepository90\Rules\Traits\ContentRepositoryTrait;
use Neos\Rector\ContentRepository90\Rules\Traits\DimensionSpacePointsTrait;
use Neos\Rector\ContentRepository90\Rules\Traits\NodeTrait;
use Neos\Rector\ContentRepository90\Rules\Traits\SubgraphTrait;
use Neos\Rector\ContentRepository90\Rules\Traits\ThisTrait;
use Neos\Rector\ContentRepository90\Rules\Traits\ValueObjectTrait;
use Neos\Rector\Generic\Rules\Traits\FunctionsTrait;

trait AllTraits
{
    use FunctionsTrait;

    use ContentRepositoryTrait;
    use NodeTrait;
    use SubgraphTrait;
    use ThisTrait;
    use ValueObjectTrait;
    use DimensionSpacePointsTrait;
}
