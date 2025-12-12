<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\NodeFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeTypeManagerAccessRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"$this->nodeTypeManager" will be rewritten.', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt::class];
    }

    /**
     * @param Node\Stmt $node
     */
    public function refactor(Node $node): ?array
    {
        if (!in_array('expr', $node->getSubNodeNames())) {
            return null;
        }

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor = new class($this->nodeTypeResolver, $this->nodeFactory) extends NodeVisitorAbstract {
            use AllTraits;

            public function __construct(
                private readonly NodeTypeResolver $nodeTypeResolver,
                protected NodeFactory $nodeFactory,
                public bool $changed = false,
                public ?Node\Expr $nodeVar = null,
            ) {
            }

            public function leaveNode(Node $node)
            {
                if (
                    $node instanceof PropertyFetch
                ) {
                    $this->nodeVar = $node;
                    if ($this->nodeTypeResolver->isObjectType($this->nodeVar, new ObjectType(\Neos\ContentRepository\Domain\Service\NodeTypeManager::class))) {
                        $this->changed = true;

                        return $this->contentRepository_getNodeTypeManager();
                    }
                }

                return null;
            }
        });

        $newExpr = $traverser->traverse([$node->expr])[0];

        if ($visitor->changed) {
            $node->expr = $newExpr;

            $contentRepository = self::assign(
                'contentRepository',
                $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))
            );

            return [
                self::withTodoComment(
                    'Make this code aware of multiple Content Repositories.',
                    new Node\Stmt\Nop(),
                ),
                $contentRepository,
                $node
            ];
        }

        return null;
    }
}
