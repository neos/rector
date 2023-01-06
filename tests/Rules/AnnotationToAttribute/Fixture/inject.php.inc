<?php

use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\Inject
     */
    protected ObjectManagerInterface $objectManager;
}

?>
-----
<?php

use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Annotations as Flow;

class SomeClass
{
    #[Flow\Inject]
    protected ObjectManagerInterface $objectManager;
}

?>
