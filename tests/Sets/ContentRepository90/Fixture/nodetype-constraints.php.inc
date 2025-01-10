<?php
declare(strict_types=1);

namespace Neos\MarketPlace\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;

class SomeClass
{
    /**
     * @Flow\Inject
     * @var NodeTypeConstraintFactory
     */
    protected $nodeTypeConstraintFactory;

    public function doSomething()
    {
        $constraints = $this->nodeTypeConstraintFactory->parseFilterString('Neos.Neos:Document');
    }

    public function doSomethingElse()
    {
        $constraints = new NodeTypeConstraints();

        $constraints->asLegacyNodeTypeFilterString();
        $constraints->getExplicitlyAllowedNodeTypeNames();
        $constraints->getExplicitlyDisallowedNodeTypeNames();
        $constraints->isWildcardAllowed();
        $constraints->matches();
        $constraints->withExplicitlyDisallowedNodeType();
    }

}
-----
<?php
declare(strict_types=1);

namespace Neos\MarketPlace\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;

class SomeClass
{
    public function doSomething()
    {
        // TODO 9.0 migration: !! The "NodeTypeConstraintFactory" has been removed in Neos 9. Please use the proper filter in subgraph finders e.g. "FindChildNodesFilter" for ContentSubgraphInterface::findChildNodes().

        $constraints = $this->nodeTypeConstraintFactory->parseFilterString('Neos.Neos:Document');
    }

    public function doSomethingElse()
    {
        // TODO 9.0 migration: !! NodeTypeConstraints is removed in Neos 9.0. Please use the proper filter in subgraph finders e.g. "FindChildNodesFilter" for ContentSubgraphInterface::findChildNodes().

        $constraints = new NodeTypeConstraints();
        // TODO 9.0 migration: !! NodeTypeConstraints has been removed in Neos 9. Please use the proper filter in subgraph finders e.g. "FindChildNodesFilter" for ContentSubgraphInterface::findChildNodes().


        $constraints->asLegacyNodeTypeFilterString();
        // TODO 9.0 migration: !! NodeTypeConstraints has been removed in Neos 9. Please use the proper filter in subgraph finders e.g. "FindChildNodesFilter" for ContentSubgraphInterface::findChildNodes().

        $constraints->getExplicitlyAllowedNodeTypeNames();
        // TODO 9.0 migration: !! NodeTypeConstraints has been removed in Neos 9. Please use the proper filter in subgraph finders e.g. "FindChildNodesFilter" for ContentSubgraphInterface::findChildNodes().

        $constraints->getExplicitlyDisallowedNodeTypeNames();
        // TODO 9.0 migration: !! NodeTypeConstraints has been removed in Neos 9. Please use the proper filter in subgraph finders e.g. "FindChildNodesFilter" for ContentSubgraphInterface::findChildNodes().

        $constraints->isWildcardAllowed();
        $constraints->matches();
        // TODO 9.0 migration: !! NodeTypeConstraints has been removed in Neos 9. Please use the proper filter in subgraph finders e.g. "FindChildNodesFilter" for ContentSubgraphInterface::findChildNodes().

        $constraints->withExplicitlyDisallowedNodeType();
    }

}
