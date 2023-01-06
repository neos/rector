<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\CompileStatic
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
    #[Flow\CompileStatic]
    public function someAction(): void
    {
    }
}

?>
