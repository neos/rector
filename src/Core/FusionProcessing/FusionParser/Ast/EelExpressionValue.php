<?php
declare(strict_types=1);

namespace Neos\Rector\Core\FusionProcessing\FusionParser\Ast;

/*
 * This file is part of the Neos.Fusion package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Rector\Core\FusionProcessing\FusionParser\AstNodeVisitorInterface;

#[Flow\Proxy(false)]
class EelExpressionValue extends AbstractPathValue
{
    public function __construct(
        /** @psalm-readonly */
        public string $value
    ) {
    }

    public function visit(AstNodeVisitorInterface $visitor, ...$args)
    {
        return $visitor->visitEelExpressionValue($this, ...$args);
    }
}
