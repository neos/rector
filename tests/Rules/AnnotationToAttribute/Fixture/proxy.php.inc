<?php

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
class SomeClass
{
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class SomeClass
{
}

?>
