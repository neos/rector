<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\Internal
     */
    public function someAction(): void
    {
    }
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    #[Flow\Internal]
    public function someAction(): void
    {
    }
}

?>
