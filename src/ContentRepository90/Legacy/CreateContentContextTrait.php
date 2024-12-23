<?php
namespace Neos\Neos\Controller;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\ContentRepository\Domain\Model\NodeData;

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
