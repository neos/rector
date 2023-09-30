<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\Transient
     */
    protected string $foo;
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    #[Flow\Transient]
    protected string $foo;
}

?>
