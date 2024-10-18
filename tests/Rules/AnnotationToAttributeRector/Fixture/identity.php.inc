<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\Identity
     */
    protected string $foo;
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    #[Flow\Identity]
    protected string $foo;
}

?>
