<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\Session(autostart=true)
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
    #[Flow\Session(autostart: true)]
    public function someAction(): void
    {
    }
}

?>
