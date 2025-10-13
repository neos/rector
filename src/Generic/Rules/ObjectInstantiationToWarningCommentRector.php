<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\ContentRepository90\Rules\AllTraits;
use Neos\Rector\Generic\ValueObject\ObjectInstantiationToWarningComment;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\NodeFactory;
use Rector\PostRector\Collector\NodesToAddCollector;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ObjectInstantiationToWarningCommentRector extends AbstractRector implements ConfigurableRectorInterface
{
    use AllTraits;

    /**
     * @var ObjectInstantiationToWarningComment[]
     */
    private array $objectInstantiationToWarningComments = [];

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Warning comments for various non-supported signals', __CLASS__, [
            new ObjectInstantiationToWarningComment(Node::class, '!! This signal "beforeMove" on Node doesn\'t exist anymore')
        ]);
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
    public function refactor(Node $node): ?Node
    {
        if (!in_array('expr', $node->getSubNodeNames())) {
            return null;
        }

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor = new class($this->nodeTypeResolver, $this->nodeFactory, $this->objectInstantiationToWarningComments) extends NodeVisitorAbstract {
            use AllTraits;

            public function __construct(
                private readonly NodeTypeResolver $nodeTypeResolver,
                protected NodeFactory $nodeFactory,
                private readonly array $objectInstantiationToWarningComments,
                public bool $changed = false,
                public ?string $commentToAdd = null,
            ) {
            }

            public function leaveNode(Node $node)
            {
                if ($node instanceof Name) {
                    foreach ($this->objectInstantiationToWarningComments as $objectInstantiationToWarningComment) {
                        if ($node && $this->nodeTypeResolver->isObjectType($node, new ObjectType($objectInstantiationToWarningComment->className))) {
                            $this->changed = true;
                            $this->commentToAdd = sprintf($objectInstantiationToWarningComment->warningMessage, $objectInstantiationToWarningComment->className);
                            return null;
                        }
                    }
                }

                return null;
            }
        });

        if ($visitor->changed) {
            return
                self::withTodoComment(
                    $visitor->commentToAdd,
                    $node
                );
        }
        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ObjectInstantiationToWarningComment::class);
        $this->objectInstantiationToWarningComments = $configuration;
    }
}
