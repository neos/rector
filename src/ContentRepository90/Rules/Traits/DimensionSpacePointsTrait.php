<?php
declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules\Traits;

use Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePoint;
use Neos\Rector\Generic\Rules\Traits\FunctionsTrait;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;

trait DimensionSpacePointsTrait
{
    use FunctionsTrait;

    /**
     * @var \Rector\Core\PhpParser\Node\NodeFactory
     */
    protected $nodeFactory;


    private function dimensionSpacePoints_toLegacyDimensionArray(
    ): Expr
    {
        return $this->nodeFactory->createFuncCall(
            'array_map',
            [
                new Expr\ArrowFunction([
                    'params' => [
                        new Param(
                            new Variable('dimensionSpacePoint'),
                            null,
                            '\\' . DimensionSpacePoint::class
                        )
                    ],
                    'expr' => $this->nodeFactory->createMethodCall('dimensionSpacePoint', 'toLegacyDimensionArray')
                ]),
                $this->iteratorToArray(new Variable('dimensionSpacePoints'))
            ]
        );
    }
}
