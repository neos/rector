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
        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);

        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);

        /** @var NodeType $nodeType */
        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
    }

    public function nodeInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $node->nodeTypeName;
        $node->nodeTypeName->value;
        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);

        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);

        /** @var NodeType $nodeType */
        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
    }

    public function traversableNodeInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $node->nodeTypeName;
        $node->nodeTypeName->value;
        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);

        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);

        /** @var NodeType $nodeType */
        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
        $nodeType->name->value;
    }
}

