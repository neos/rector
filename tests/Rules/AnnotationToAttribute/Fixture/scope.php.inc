<?php

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class SomeClass
{
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

#[Flow\Scope('singleton')]
class SomeClass
{
}

?>
