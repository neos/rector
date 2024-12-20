<?php

declare (strict_types=1);
namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeTypeManagerAccessRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector
    )
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return CodeSampleLoader::fromFile('"$this->nodeTypeManager" will be rewritten.', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\PropertyFetch::class];
    }
    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node) : ?Node
    {
        assert($node instanceof Node\Expr\PropertyFetch);

        if (!$this->isObjectType($node, new ObjectType(\Neos\ContentRepository\Domain\Service\NodeTypeManager::class))) {
            return null;
        }

        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::withTodoComment(
                    'Make this code aware of multiple Content Repositories.',
                    self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
                )
            ],
            $node
        );

        return $this->contentRepository_getNodeTypeManager();
    }
}
