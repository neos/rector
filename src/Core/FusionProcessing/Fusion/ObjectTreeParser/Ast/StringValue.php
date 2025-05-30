<?php
declare(strict_types=1);

namespace Neos\Rector\Core\FusionProcessing\Fusion\ObjectTreeParser\Ast;

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
use Neos\Rector\Core\FusionProcessing\Fusion\ObjectTreeParser\AstNodeVisitorInterface;

/** @internal */
#[Flow\Proxy(false)]
final readonly class StringValue extends AbstractPathValue
{
    public function __construct(
        public string $value
    ) {
    }

    public function visit(AstNodeVisitorInterface $visitor, mixed ...$args)
    {
        return $visitor->visitStringValue($this, ...$args);
    }
}
