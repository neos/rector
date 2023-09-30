<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\SkipCsrfProtection
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
    #[Flow\SkipCsrfProtection]
    public function someAction(): void
    {
    }
}

?>
