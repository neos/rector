<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\ContentRepository\Core\NodeType\NodeTypeName;
use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Neos\ContentRepository\Core\NodeType\NodeType;
use PhpParser\NodeDumper;

final class NodeGetPropertyNamesRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"$nodeType->allowsGrandchildNodeType($parentNodeName, $nodeType)" will be rewritten.', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if (!$this->isObjectType($node->var, new ObjectType(NodeLegacyStub::class))) {
            return null;
        }

        if (!$this->isName($node->name, 'getPropertyNames')) {
            return null;
        }

        return
            $this->nodeFactory->createFuncCall('array_keys', [
                $this->nodeFactory->createFuncCall('iterator_to_array', [
                            $this->nodeFactory->createPropertyFetch($node->var, 'properties'),
                ]),
            ]);
    }
}
