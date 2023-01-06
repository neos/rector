<?php

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Entity()
 */
class SomeClass
{
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

#[Flow\Entity]
class SomeClass
{
}

?>
