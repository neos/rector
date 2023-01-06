<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @Flow\InjectConfiguration(path="tests.functional.settingInjection.someSetting", package="Neos.Flow")
     */
    protected string $injectedSetting;
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;

class SomeClass
{
    #[Flow\InjectConfiguration(path: 'tests.functional.settingInjection.someSetting', package: 'Neos.Flow')]
    protected string $injectedSetting;
}

?>
