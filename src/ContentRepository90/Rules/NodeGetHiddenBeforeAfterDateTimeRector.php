<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeGetHiddenBeforeAfterDateTimeRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeInterface::getHiddenBeforeDateTime()", "NodeInterface::setHiddenBeforeDateTime()", "NodeInterface::getHiddenAfterDateTime()" and "NodeInterface::setHiddenAfterDateTime()" will be rewritten', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if (!$this->isObjectType($node->var, new ObjectType(\Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub::class))) {
            return null;
        }

        if (
            !$this->isName($node->name, 'getHiddenBeforeDateTime')
            && !$this->isName($node->name, 'setHiddenBeforeDateTime')
            && !$this->isName($node->name, 'getHiddenAfterDateTime')
            && !$this->isName($node->name, 'setHiddenAfterDateTime')
        ) {
            return null;
        }

        $comment = 'Timed publishing has been conceptually changed and has been extracted into a dedicated package. Please check https://github.com/neos/timeable-node-visibility for further details.';

        if ($this->isName($node->name, 'getHiddenBeforeDateTime')) {
            $newNode = $this->nodeFactory->createMethodCall($node->var, 'getProperty', ['enableAfterDateTime']);
        } elseif ($this->isName($node->name, 'getHiddenAfterDateTime')) {
            $newNode = $this->nodeFactory->createMethodCall($node->var, 'getProperty', ['disableAfterDateTime']);
        } else {
            $newNode = $node;
            $comment .= PHP_EOL . '// Use the "SetNodeProperties" command to change property values for "enableAfterDateTime" or "disableAfterDateTime".';
        }

//        $this->nodesToAddCollector->addNodesBeforeNode(
//            [
//                self::todoComment($comment)
//            ],
//            $node
//        );

        return $newNode;
    }
}
