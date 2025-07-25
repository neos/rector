<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\Generic\ValueObject\ObjectInstantiationToWarningComment;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ObjectInstantiationToWarningCommentRector extends AbstractRector implements ConfigurableRectorInterface
{
    use AllTraits;

    /**
     * @var ObjectInstantiationToWarningComment[]
     */
    private array $objectInstantiationToWarningComments = [];

    public function __construct(
    ) {
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
        return [Name::class];
    }

    /**
     * @param \PhpParser\Node\Name $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Name);

//        if ($node->getAttribute(AttributeKey::PARENT_NODE) instanceof Node\Stmt\UseUse) {
//            return null;
//        }


        foreach ($this->objectInstantiationToWarningComments as $objectInstantiationToWarningComment) {
            if (!$this->isObjectType($node, new ObjectType($objectInstantiationToWarningComment->className))) {
                continue;
            }

//            $this->nodesToAddCollector->addNodesBeforeNode(
//                [
//                    self::todoComment(sprintf($objectInstantiationToWarningComment->warningMessage, $objectInstantiationToWarningComment->className))
//                ],
//                $node
//            );
        }
        return $node;
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
