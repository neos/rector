<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\IgnoreValidation
     */
    protected $foo;

    /**
     * @Flow\IgnoreValidation(argumentName="resource")
     * @Flow\IgnoreValidation(argumentName="resourceType")
     */
    public function someAction(Resource $resource, string $resourceType = ''): void
    {
    }
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    #[Flow\IgnoreValidation]
    protected $foo;

    #[Flow\IgnoreValidation(argumentName: 'resource')]
    #[Flow\IgnoreValidation(argumentName: 'resourceType')]
    public function someAction(Resource $resource, string $resourceType = ''): void
    {
    }
}

?>
