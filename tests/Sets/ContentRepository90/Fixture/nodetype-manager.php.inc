<?php
declare(strict_types=1);

namespace Neos\MarketPlace\Domain\Model;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\ContentRepository\Exception\NodeTypeNotFoundException;
use Neos\Flow\Annotations as Flow;
use Neos\MarketPlace\Exception;

class Storage
{
    /**
     * @var NodeTypeManager
     * @Flow\Inject
     */
    protected $nodeTypeManager;

    /**
     * @throws Exception
     * @throws NodeTypeNotFoundException
     */
    public function createVendor(string $vendor): NodeInterface
    {
        $vendor = Slug::create($vendor);
        $node = $this->node()->getNode($vendor);

        if ($node === null) {
            $node = $this->node()->createNode($vendor, $this->nodeTypeManager->getNodeType('Neos.MarketPlace:Vendor'));
            $node->setProperty('uriPathSegment', $vendor);
            $node->setProperty('title', $vendor);
        }

        $nodeTypeManager = $this->getNodeTypeManager();
        $nodeTypeManager->createNodeType('Neos.MarketPlace:New');
        return $node;
    }

    public function getNodeTypeManager(): NodeTypeManager
    {
        return $this->nodeTypeManager;
    }
}
-----
<?php
declare(strict_types=1);

namespace Neos\MarketPlace\Domain\Model;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\ContentRepository\Exception\NodeTypeNotFoundException;
use Neos\Flow\Annotations as Flow;
use Neos\MarketPlace\Exception;

class Storage
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;

    /**
     * @throws Exception
     * @throws NodeTypeNotFoundException
     */
    public function createVendor(string $vendor): \Neos\ContentRepository\Core\Projection\ContentGraph\Node
    {
        $vendor = Slug::create($vendor);
        $node = $this->node()->getNode($vendor);

        if ($node === null) {
            // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
            $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
            $node = $this->node()->createNode($vendor, $contentRepository->getNodeTypeManager()->getNodeType('Neos.MarketPlace:Vendor'));
            $node->setProperty('uriPathSegment', $vendor);
            $node->setProperty('title', $vendor);
        }

        $nodeTypeManager = $this->getNodeTypeManager();
        // TODO 9.0 migration: !! NodeTypeManager::createNodeType() was never implemented and is removed in Neos 9.0.

        $nodeTypeManager->createNodeType('Neos.MarketPlace:New');
        return $node;
    }

    public function getNodeTypeManager(): \Neos\ContentRepository\Core\NodeType\NodeTypeManager
    {
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        return $contentRepository->getNodeTypeManager();
    }
}
