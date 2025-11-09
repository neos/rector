<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\ContentRepository\Core\NodeType\NodeType;
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

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeTypeGetTypeOfAutoCreatedChildNodeRector extends AbstractRector
{
    use AllTraits;

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"$nodeType->getTypeOfAutoCreatedChildNode($nodeName)" will be rewritten.', __CLASS__);
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
                        $node->name->toString() === 'getTypeOfAutoCreatedChildNode'
                    ) {
                        if ($this->nodeTypeResolver->isObjectType($node->var, new ObjectType(NodeType::class))) {
                            $this->changed = true;

                            return $this->nodeFactory->createMethodCall(
                                $this->contentRepository_getNodeTypeManager(),
                                'getNodeType',
                                [$this->nodeFactory->createMethodCall(
                                    $this->nodeFactory->createPropertyFetch(
                                        $node->var,
                                        'tetheredNodeTypeDefinitions'
                                    ),
                                    'get',
                                    $node->args
                                )]
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
                'Make this code aware of multiple Content Repositories. If you have a Node object around you can use $node->contentRepositoryId.',
                self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
            ),
            $node,
        ];
    }

}
