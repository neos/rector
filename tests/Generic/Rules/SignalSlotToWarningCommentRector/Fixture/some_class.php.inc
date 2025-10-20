<?php

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\SignalSlot\Dispatcher;
use Neos\ContentRepository\Domain\Model\Node;

class Package extends BasePackage
{
    public function boot(Bootstrap $bootstrap)
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect(
            Node::class,
            'beforeMove',
            SomeOtherClass::class,
            'someMethod'
        );

        $dispatcher->connect(
            'Neos\ContentRepository\Domain\Model\Node',
            'afterMove',
            SomeOtherClass::class,
            'someMethod'
        );

        $dispatcher->connect(
            Node::class,
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
use Neos\ContentRepository\Domain\Model\Node;

class Package extends BasePackage
{
    public function boot(Bootstrap $bootstrap)
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        // TODO 9.0 migration: Signal "beforeMove" doesn't exist anymore


        $dispatcher->connect(
            Node::class,
            'beforeMove',
            SomeOtherClass::class,
            'someMethod'
        );
        // TODO 9.0 migration: Signal "afterMove" doesn't exist anymore


        $dispatcher->connect(
            'Neos\ContentRepository\Domain\Model\Node',
            'afterMove',
            SomeOtherClass::class,
            'someMethod'
        );

        $dispatcher->connect(
            Node::class,
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
