<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\MapRequestBody("$someArgument")
     */
    public function someAction(array $someArgument): void
    {
    }
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    #[Flow\MapRequestBody('$someArgument')]
    public function someAction(array $someArgument): void
    {
    }
}

?>
