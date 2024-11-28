<?php

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\SignalSlot\Dispatcher;
use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class Package extends BasePackage
{
    public function boot(Bootstrap $bootstrap)
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect(
            NodeLegacyStub::class,
            'beforeMove',
            SomeOtherClass::class,
            'someMethod'
        );

        $dispatcher->connect(
            'Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub',
            'afterMove',
            SomeOtherClass::class,
            'someMethod'
        );

        $dispatcher->connect(
            NodeLegacyStub::class,
            'otherMethod',
            SomeOtherClass::class,
            'someMethod'
        );

        $dispatcher->connect(
            OtherClass::class,
            'afterMove',
            SomeOtherClass::class,
            'someMethod'
        );
    }
}

?>
-----
<?php

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\SignalSlot\Dispatcher;
use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class Package extends BasePackage
{
    public function boot(Bootstrap $bootstrap)
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        // TODO 9.0 migration: Signal "beforeMove" doesn't exist anymore


        $dispatcher->connect(
            NodeLegacyStub::class,
            'beforeMove',
            SomeOtherClass::class,
            'someMethod'
        );
        // TODO 9.0 migration: Signal "afterMove" doesn't exist anymore


        $dispatcher->connect(
            'Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub',
            'afterMove',
            SomeOtherClass::class,
            'someMethod'
        );

        $dispatcher->connect(
            NodeLegacyStub::class,
            'otherMethod',
            SomeOtherClass::class,
            'someMethod'
        );

        $dispatcher->connect(
            OtherClass::class,
            'afterMove',
            SomeOtherClass::class,
            'someMethod'
        );
    }
}

?>
