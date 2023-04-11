<?php

use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;

class SomeClass
{
    public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
        return $context->getRootNode();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;

class SomeClass
{
    public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
        // TODO 9.0 migration: !! MEGA DIRTY CODE! Ensure to rewrite this; by getting rid of LegacyContextStub.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\Factory\ContentRepositoryId::fromString('default'));
        $workspace = $contentRepository->getWorkspaceFinder()->findOneByName(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($context->workspaceName ?? 'live'));
        $rootNodeAggregate = $contentRepository->getContentGraph()->findRootNodeAggregateByType($workspace->currentContentStreamId, \Neos\ContentRepository\Core\NodeType\NodeTypeName::fromString('Neos.Neos:Sites'));
        $subgraph = $contentRepository->getContentGraph()->getSubgraph($workspace->currentContentStreamId, \Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePoint::fromLegacyDimensionArray($context->dimensions ?? []), $context->invisibleContentShown ? \Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints::withoutRestrictions() : \Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints::frontend());
        return $subgraph->findNodeById($rootNodeAggregate->nodeAggregateId);
    }
}

?>
