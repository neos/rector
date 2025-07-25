<?php

namespace Neos\ContentRepository\Domain\Projection\Content;

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
use Traversable;

/**
 * @implements \IteratorAggregate<int, TraversableNodeInterface>
 * @deprecated
 */
class TraversableNodes implements \IteratorAggregate, \Countable
{

    /**
     * @return TraversableNodeInterface[]|\ArrayIterator<TraversableNodeInterface>
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator();
    }

    public function count(): int
    {
    }
}
