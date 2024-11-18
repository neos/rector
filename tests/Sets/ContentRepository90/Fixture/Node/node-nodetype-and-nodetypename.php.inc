<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;

class SomeClass extends AnotherClass
{

    public function node(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $node->getNodeTypeName();
        $node->getNodeType()->getName();

        $nodeType = $node->getNodeType();
        $nodeType->getName();

        /** @var NodeType $nodeType */
        $nodeType = $node->getNodeType();
        $nodeType->getName();
    }

    public function nodeInterface(\Neos\ContentRepository\Domain\Model\NodeInterface $node)
    {
        $node->getNodeTypeName();
        $node->getNodeType()->getName();

        $nodeType = $node->getNodeType();
        $nodeType->getName();

        /** @var NodeType $nodeType */
        $nodeType = $node->getNodeType();
        $nodeType->getName();
    }

    public function traversableNodeInterface(\Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface $node)
    {
        $node->getNodeTypeName();
        $node->getNodeType()->getName();

        $nodeType = $node->getNodeType();
        $nodeType->getName();

        /** @var NodeType $nodeType */
        $nodeType = $node->getNodeType();
        $nodeType->getName();
    }
}

-----
<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;

class SomeClass extends AnotherClass
{

    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
    public function node(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $node->nodeTypeName;
        $node->nodeTypeName->value;
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));

        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));

        /** @var NodeType $nodeType */
        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
    }

    public function nodeInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $node->nodeTypeName;
        $node->nodeTypeName->value;
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));

        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));

        /** @var NodeType $nodeType */
        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
    }

    public function traversableNodeInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $node->nodeTypeName;
        $node->nodeTypeName->value;
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));

        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));

        /** @var NodeType $nodeType */
        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
    }
}

