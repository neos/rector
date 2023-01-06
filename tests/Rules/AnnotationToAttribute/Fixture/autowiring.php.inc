<?php

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Autowiring(false)
 */
class SomeClass
{
    /**
     * @Flow\Autowiring(enabled=false)
     */
    public function someAction(Resource $resource, string $resourceType = ''): void
    {
    }
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

#[Flow\Autowiring(false)]
class SomeClass
{
    #[Flow\Autowiring(enabled: false)]
    public function someAction(Resource $resource, string $resourceType = ''): void
    {
    }
}

?>
