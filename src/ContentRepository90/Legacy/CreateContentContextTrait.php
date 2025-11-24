<?php

namespace Neos\Neos\Controller;

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;

/**
 * @deprecated
 */
trait CreateContentContextTrait
{
    protected function createContentContext($workspaceName, array $dimensions = []): ContentContext
    {
        return new ContentContext($workspaceName, $dimensions);
    }

    protected function createContextMatchingNodeData(NodeData $nodeData): ContentContext
    {
        return new ContentContext();

    }
}
