<?php

class SomeClass
{
    public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
        $context->getFirstLevelNodeCache()->reset();
        $context->getFirstLevelNodeCache()->someOtherMethod();
    }
}

?>
-----
<?php

class SomeClass
{
    public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
    }
}

?>
