<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $label = $node->getLabel();
    }
}

?>
-----
<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\Neos\Domain\NodeLabel\NodeLabelGeneratorInterface $nodeLabelGenerator;
    public function run(NodeLegacyStub $node)
    {
        $label = $this->nodeLabelGenerator->getLabel($node);
    }
}

?>
