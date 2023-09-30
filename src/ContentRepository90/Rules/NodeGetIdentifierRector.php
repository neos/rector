<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeGetIdentifierRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeInterface::getIdentifier()" will be rewritten', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if (!$this->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Core\Projection\ContentGraph\Node::class))) {
            return null;
        }
        if (!$this->isName($node->name, 'getIdentifier')) {
            return null;
        }

        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::todoComment('Check if you could change your code to work with the NodeAggregateId value object instead.')
            ],
            $node
        );

        $propertyFetchAggregateId = $this->nodeFactory->createPropertyFetch($node->var, 'nodeAggregateId');
        return $this->nodeFactory->createPropertyFetch($propertyFetchAggregateId, 'value');
    }
}
