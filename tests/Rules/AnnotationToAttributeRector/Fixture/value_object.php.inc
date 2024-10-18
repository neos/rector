<?php

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\ValueObject(embedded=false)
 */
class SomeClass
{
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

#[Flow\ValueObject(embedded: false)]
class SomeClass
{
}

?>
