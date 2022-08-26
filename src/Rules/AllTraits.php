<?php
declare (strict_types=1);

namespace Neos\Rector\Rules;

use Neos\Rector\Rules\Traits\ContentRepositoryTrait;
use Neos\Rector\Rules\Traits\FunctionsTrait;
use Neos\Rector\Rules\Traits\NodeHiddenStateFinderTrait;
use Neos\Rector\Rules\Traits\NodeTrait;
use Neos\Rector\Rules\Traits\SubgraphTrait;
use Neos\Rector\Rules\Traits\ThisTrait;
use Neos\Rector\Rules\Traits\ValueObjectTrait;

trait AllTraits
{
    use FunctionsTrait;

    use ContentRepositoryTrait;
    use NodeHiddenStateFinderTrait;
    use NodeTrait;
    use SubgraphTrait;
    use ThisTrait;
    use ValueObjectTrait;
}
