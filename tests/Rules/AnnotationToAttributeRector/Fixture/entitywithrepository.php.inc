<?php

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Entity(repositoryClass=FooRepository::class, readOnly=true)
 */
class SomeClass
{
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

#[Flow\Entity(repositoryClass: FooRepository::class, readOnly: true)]
class SomeClass
{
}

?>
