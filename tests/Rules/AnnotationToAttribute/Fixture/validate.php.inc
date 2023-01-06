<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\Validate(type="StringLength", options={ "minimum"=0, "maximum"=40 })
     */
    protected $foo;

    /**
     * @Flow\Validate(type="StringLength", options={ "minimum"=0, "maximum"=40 })
     * @Flow\Validate(argumentName="resource", type="NotEmpty")
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
    #[Flow\Validate(type: 'StringLength', options: ['minimum' => 0, 'maximum' => 40])]
    protected $foo;

    #[Flow\Validate(type: 'StringLength', options: ['minimum' => 0, 'maximum' => 40])]
    #[Flow\Validate(argumentName: 'resource', type: 'NotEmpty')]
    public function someAction(Resource $resource, string $resourceType = ''): void
    {
    }
}

?>
