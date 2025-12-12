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

final class WorkspaceGetBaseWorkspaceRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Workspace::getBaseWorkspace()" will be rewritten', __CLASS__);
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
                    $node instanceof MethodCall &&
                    $node->name instanceof Identifier &&
                    $node->name->toString() === 'getBaseWorkspace'
                ) {
                    $this->nodeVar = $node->var;
                    if ($this->nodeTypeResolver->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Core\SharedModel\Workspace\Workspace::class))) {
                        $this->changed = true;

                        return $this->nodeFactory->createMethodCall(
                            new Variable('contentRepository'),
                            'findWorkspaceByName',
                            [$this->nodeFactory->createPropertyFetch($node->var, 'baseWorkspaceName')]
                        );
                    }
                }

                return null;
            }
        });

        $newExpr = $traverser->traverse([$node->expr])[0];

        if ($visitor->changed) {
            $node->expr = $newExpr;

            return [
                new Nop(), // Needed, to render the comment below
                self::withTodoComment(
                    'Check if you could change your code to work with the WorkspaceName value object instead and make this code aware of multiple Content Repositories.',
                    self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
                ),
                $node,
            ];
        }

        return null;
    }
}
