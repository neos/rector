<?php

namespace Neos\Rector\Test;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Service\Context;

class SomeClass extends AnotherClass
{
    /**
     * @Flow\Inject
     * @var \Neos\Neos\Domain\Service\NodeSearchService
     */
    private $nodeSearchService;

    /**
     * @Flow\Inject
     * @var \Neos\Neos\Domain\Service\NodeSearchServiceInterface
     */
    private $nodeSearchServiceInterface;

    public function startingPointNodeIsGiven(Node $node, Context $context)
    {
        $term = "term";
        $searchNodeTypes = [];
        $nodes = $this->nodeSearchService->findByProperties($term, $searchNodeTypes, $context, $node);
    }

    public function startingPointNodeIsNotGiven(Context $context)
    {
        $term = "term";
        $searchNodeTypes = [];
        $nodes = $this->nodeSearchService->findByProperties($term, $searchNodeTypes, $context);
    }

    public function startingPointNodeIsGivenUsingInterface(Node $node, Context $context)
    {
        $term = "term";
        $searchNodeTypes = [];
        $nodes = $this->nodeSearchServiceInterface->findByProperties($term, $searchNodeTypes, $context, $node);
    }
}

-----
<?php

namespace Neos\Rector\Test;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Service\Context;

class SomeClass extends AnotherClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
    public function startingPointNodeIsGiven(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node, \Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
        $term = "term";
        $searchNodeTypes = [];
        // TODO 9.0 migration: This could be a suitable replacement. Please check if all your requirements are still fulfilled.
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $nodes = $subgraph->findDescendantNodes($node->aggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindDescendantNodesFilter::create(nodeTypes: \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\NodeType\NodeTypeCriteria::create(\Neos\ContentRepository\Core\NodeType\NodeTypeNames::fromStringArray($searchNodeTypes), \Neos\ContentRepository\Core\NodeType\NodeTypeNames::createEmpty()), searchTerm: $term));
    }

    public function startingPointNodeIsNotGiven(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
        $term = "term";
        $searchNodeTypes = [];
        // TODO 9.0 migration: The replacement needs a node as starting point for the search. Please provide a node, to make this replacement working.
        $node = 'we-need-a-node-here';
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $nodes = $subgraph->findDescendantNodes($node->aggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindDescendantNodesFilter::create(nodeTypes: \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\NodeType\NodeTypeCriteria::create(\Neos\ContentRepository\Core\NodeType\NodeTypeNames::fromStringArray($searchNodeTypes), \Neos\ContentRepository\Core\NodeType\NodeTypeNames::createEmpty()), searchTerm: $term));
    }

    public function startingPointNodeIsGivenUsingInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node, \Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
        $term = "term";
        $searchNodeTypes = [];
        // TODO 9.0 migration: This could be a suitable replacement. Please check if all your requirements are still fulfilled.
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $nodes = $subgraph->findDescendantNodes($node->aggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindDescendantNodesFilter::create(nodeTypes: \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\NodeType\NodeTypeCriteria::create(\Neos\ContentRepository\Core\NodeType\NodeTypeNames::fromStringArray($searchNodeTypes), \Neos\ContentRepository\Core\NodeType\NodeTypeNames::createEmpty()), searchTerm: $term));
    }
}

