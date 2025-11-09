<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PhpParser\Node\Expr\Assign;
use PhpParser\NodeDumper;

final class ContextIsLiveRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;
    use ContextRectorTrait;

    public function __construct(
    ) {
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"ContentContext::isLive()" will be replaced with RenderingModeService::findByCurrentUser().', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        $oldContextMethod = 'isLive';
        if ($this->isContextWithMethod($node, $oldContextMethod)) {

            $renderingModeService = $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createPropertyFetch(
                    'this',
                    'renderingModeService'
                ),
                'findByCurrentUser'
            );

            $isEdit = $this->nodeFactory->createPropertyFetch(
                $renderingModeService, 'isEdit'
            );
            $isPreview = $this->nodeFactory->createPropertyFetch(
                $renderingModeService, 'isPreview'
            );

            $replacementExpression = new Node\Expr\BinaryOp\BooleanAnd(
                new Node\Expr\BooleanNot(
                    $isEdit
                ),
                new Node\Expr\BooleanNot(
                    $isPreview
                ),
            );

            return $replacementExpression;
        }

        return null;
    }

}
