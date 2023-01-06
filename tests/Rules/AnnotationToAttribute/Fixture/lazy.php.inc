<?php

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Lazy
 */
class SomeClass
{
    /**
     * @Flow\Lazy
     */
    protected $foo;
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

#[Flow\Lazy]
class SomeClass
{
    #[Flow\Lazy]
    protected $foo;
}

?>
