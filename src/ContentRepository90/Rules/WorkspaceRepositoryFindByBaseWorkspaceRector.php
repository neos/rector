<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\NodeFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class WorkspaceRepositoryFindByBaseWorkspaceRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"WorkspaceRepository::findByBaseWorkspace()" will be rewritten.', __CLASS__);
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
        $traverser->addVisitor(
            $visitor = new class($this->nodeTypeResolver, $this->nodeFactory) extends NodeVisitorAbstract {
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
                        $node->name->toString() === 'findByBaseWorkspace'
                    ) {
                        if ($this->nodeTypeResolver->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Domain\Repository\WorkspaceRepository::class))) {
                            $this->changed = true;

                            return $this->nodeFactory->createMethodCall(
                                $this->nodeFactory->createMethodCall(
                                    new Variable('contentRepository'),
                                    'findWorkspaces',
                                    []
                                ),
                                'getDependantWorkspaces',
                                [
                                    $this->workspaceName_fromString($node->args[0]->value)
                                ]
                            );
                        }
                    }
                    return null;
                }
            });

        /** @var Node\Expr $newExpr */
        $newExpr = $traverser->traverse([$node->expr])[0];

        if (!$visitor->changed) {
            return null;
        }

        $node->expr = $newExpr;

        return [
            new Nop(), // Needed, to render the comment below
            self::withTodoComment(
                'Make this code aware of multiple Content Repositories.',
                self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
            ),
            $node,
        ];
    }
}
