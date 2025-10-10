<?php

use Neos\ContentRepository\Domain\Factory\NodeFactory;

class SomeClass
{
    /**
     * @var NodeFactory
     * @Flow\Inject
     */
    protected $nodeFactory;
    public function run()
    {
        $this->nodeFactory->reset();
        return $this->nodeFactory->reset();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Factory\NodeFactory;

class SomeClass
{
    /**
     * @var NodeFactory
     * @Flow\Inject
     */
    protected $nodeFactory;
    public function run()
    {
        return;
    }
}

?>
