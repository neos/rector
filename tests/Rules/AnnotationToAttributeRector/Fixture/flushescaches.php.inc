<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\FlushesCaches
     */
    public function someCommand(): void
    {
    }
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    #[Flow\FlushesCaches]
    public function someCommand(): void
    {
    }
}

?>
