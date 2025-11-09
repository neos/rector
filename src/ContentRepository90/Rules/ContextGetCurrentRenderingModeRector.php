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

final class ContextGetCurrentRenderingModeRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;
    use ContextRectorTrait;

    public function __construct(
    ) {
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"ContentContext::getCurrentRenderingMode()" will be replaced with RenderingModeService::findByCurrentUser().', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        $oldContextMethod = 'getCurrentRenderingMode';
        if (
            $this->isContextWithMethod($node, $oldContextMethod)
        ) {

            return $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createPropertyFetch(
                    'this',
                    'renderingModeService'
                ),
                'findByCurrentUser'
            );
        }

        return null;
    }

}
