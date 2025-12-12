<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId;
use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\NodeFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class WorkspaceSetTitleRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Workspace::setTitle()" will be rewritten', __CLASS__);
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt::class];
    }

    /**
     * @param Node\Stmt $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!in_array('expr', $node->getSubNodeNames())) {
            return null;
        }

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor = new class($this->nodeTypeResolver, $this->nodeFactory) extends NodeVisitorAbstract {
            public function __construct(
                private readonly NodeTypeResolver $nodeTypeResolver,
                private readonly NodeFactory $nodeFactory,
                public bool $changed = false,
            ) {
            }

            public function leaveNode(Node $node)
            {
                if (
                    $node instanceof MethodCall &&
                    $node->name instanceof Identifier &&
                    $node->name->toString() === 'setTitle'
                ) {
                    if ($this->nodeTypeResolver->isObjectType($node->var, new ObjectType(Workspace::class))) {
                        $this->changed = true;

                        $serviceCall = $this->nodeFactory->createMethodCall(
                            $this->nodeFactory->createPropertyFetch('this', 'workspaceService'),
                            'setWorkspaceTitle',
                            [
                                $this->nodeFactory->createStaticCall(ContentRepositoryId::class, 'fromString', [new String_('default')]),
                                $this->nodeFactory->createPropertyFetch($node->var, 'workspaceName'),
                                $this->nodeFactory->createStaticCall(\Neos\Neos\Domain\Model\WorkspaceTitle::class, 'fromString', [$node->args[0]])
                            ]
                        );
                        return $serviceCall;
                    }
                }
                return null;
            }
        });

        $newExpr = $traverser->traverse([$node->expr])[0];

        if ($visitor->changed) {
            $node->expr = $newExpr;
            self::withTodoComment('Make this code aware of multiple Content Repositories.', $node);
            return $node;
        }

        return null;
    }
}