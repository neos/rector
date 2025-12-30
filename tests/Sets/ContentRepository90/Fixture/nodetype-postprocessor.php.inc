<?php
declare(strict_types=1);

namespace Neos\MarketPlace\Domain\Model;

use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;
use Neos\Flow\Annotations as Flow;

class SomeClass implements NodeTypePostprocessorInterface
{
    public function doSomethingElse()
    {
    }

}
-----
<?php
declare(strict_types=1);

namespace Neos\MarketPlace\Domain\Model;

use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;
use Neos\Flow\Annotations as Flow;

class SomeClass implements \Neos\ContentRepository\Core\NodeType\NodeTypePostprocessorInterface
{
    public function doSomethingElse()
    {
    }

}
