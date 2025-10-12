<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\NodeFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeGetContextGetWorkspaceRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeInterface::getContext()::getWorkspace()" will be rewritten', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class, Node\Stmt\Return_::class];
    }

    /**
     * @param Node<Node\Stmt\Expression,Node\Stmt\Return_> $node
     */
    public function refactor(Node $node): ?array
    {
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
                    $node instanceof MethodCall &&
                    $node->name instanceof Identifier &&
                    $node->name->toString() === 'getWorkspace' &&
                    $node->var instanceof MethodCall &&
                    $node->var->name instanceof Identifier &&
                    $node->var->name->toString() === 'getContext'
                ) {
                    $this->nodeVar = $node->var->var;
                    $type = $this->nodeTypeResolver->getType($this->nodeVar);
                    if ($type instanceof ObjectType && $type->getClassName() === NodeLegacyStub::class) {
                        $this->changed = true;

                        return $this->contentRepository_findWorkspaceByName(
                            $this->nodeFactory->createPropertyFetch($this->nodeVar, 'workspaceName')
                        );
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
                $this->this_contentRepositoryRegistry_get(
                    $this->nodeFactory->createPropertyFetch($visitor->nodeVar, 'contentRepositoryId')
                )
            );

            return [
                $contentRepository, $node
            ];
        }

        return null;
    }
}
