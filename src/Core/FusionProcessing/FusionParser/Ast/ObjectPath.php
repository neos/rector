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
class ObjectPath extends AbstractNode
{
    /**
     * @psalm-readonly
     * @var AbstractPathSegment[]
     */
    public $segments;

    public function __construct(AbstractPathSegment ...$segments)
    {
        $this->segments = $segments;
    }

    public function visit(AstNodeVisitorInterface $visitor, ...$args)
    {
        return $visitor->visitObjectPath($this, ...$args);
    }
}
