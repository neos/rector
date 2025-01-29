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
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        $workspace = $contentRepository->findWorkspaceByName(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($context->workspaceName ?? 'live'));
        $rootNodeAggregate = $contentRepository->getContentGraph($workspace->workspaceName)->findRootNodeAggregateByType(\Neos\ContentRepository\Core\NodeType\NodeTypeName::fromString('Neos.Neos:Sites'));
        $subgraph = $contentRepository->getContentGraph($workspace->workspaceName)->getSubgraph(\Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePoint::fromLegacyDimensionArray($context->dimensions ?? []), $context->invisibleContentShown ? \Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints::withoutRestrictions() : \Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints::default());
        return $subgraph->findNodeById($rootNodeAggregate->nodeAggregateId);
    }
}

?>
