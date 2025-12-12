<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $label = $node->getLabel();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\Neos\Domain\NodeLabel\NodeLabelGeneratorInterface $nodeLabelGenerator;
    public function run(Node $node)
    {
        $label = $this->nodeLabelGenerator->getLabel($node);
    }
}

?>
