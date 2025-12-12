<?php

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\SignalSlot\Dispatcher;
use Neos\Neos\Service\PublishingService;

class Package extends BasePackage
{
    /**
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        /**
         * @var Dispatcher
         */
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect(\Neos\ContentRepository\Domain\Model\Node::class, 'beforeNodeMove', function () { return 'foo'; });
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'afterNodeMove', RouterCachingService::class, 'flushCaches');
        $dispatcher->connect(\Neos\ContentRepository\Domain\Model\Node::class, 'beforeNodeCopy', function () { return 'foo'; });
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'afterNodeCopy', RouterCachingService::class, 'flushCaches');
        $dispatcher->connect(\Neos\ContentRepository\Domain\Model\Node::class, 'nodePathChanged', function () { return 'foo'; });
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'beforeNodeCreate', RouterCachingService::class, 'flushCaches');
        $dispatcher->connect(\Neos\ContentRepository\Domain\Model\Node::class, 'afterNodeCreate', function () { return 'foo'; });
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'nodeAdded', RouterCachingService::class, 'flushCaches');
        $dispatcher->connect(\Neos\ContentRepository\Domain\Model\Node::class, 'nodeUpdated', function () { return 'foo'; });
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'nodeRemoved', RouterCachingService::class, 'flushCaches');
        $dispatcher->connect(\Neos\ContentRepository\Domain\Model\Node::class, 'beforeNodePropertyChange', function () { return 'foo'; });
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'nodePropertyChanged', RouterCachingService::class, 'flushCaches');
    }
}
-----
<?php

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\SignalSlot\Dispatcher;
use Neos\Neos\Service\PublishingService;

class Package extends BasePackage
{
    /**
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        /**
         * @var Dispatcher
         */
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        // TODO 9.0 migration: The signal "beforeNodeMove" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect(\Neos\ContentRepository\Core\Projection\ContentGraph\Node::class, 'beforeNodeMove', function () { return 'foo'; });
        // TODO 9.0 migration: The signal "afterNodeMove" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'afterNodeMove', RouterCachingService::class, 'flushCaches');
        // TODO 9.0 migration: The signal "beforeNodeCopy" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect(\Neos\ContentRepository\Core\Projection\ContentGraph\Node::class, 'beforeNodeCopy', function () { return 'foo'; });
        // TODO 9.0 migration: The signal "afterNodeCopy" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'afterNodeCopy', RouterCachingService::class, 'flushCaches');
        // TODO 9.0 migration: The signal "nodePathChanged" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect(\Neos\ContentRepository\Core\Projection\ContentGraph\Node::class, 'nodePathChanged', function () { return 'foo'; });
        // TODO 9.0 migration: The signal "beforeNodeCreate" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'beforeNodeCreate', RouterCachingService::class, 'flushCaches');
        // TODO 9.0 migration: The signal "afterNodeCreate" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect(\Neos\ContentRepository\Core\Projection\ContentGraph\Node::class, 'afterNodeCreate', function () { return 'foo'; });
        // TODO 9.0 migration: The signal "nodeAdded" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'nodeAdded', RouterCachingService::class, 'flushCaches');
        // TODO 9.0 migration: The signal "nodeUpdated" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect(\Neos\ContentRepository\Core\Projection\ContentGraph\Node::class, 'nodeUpdated', function () { return 'foo'; });
        // TODO 9.0 migration: The signal "nodeRemoved" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'nodeRemoved', RouterCachingService::class, 'flushCaches');
        // TODO 9.0 migration: The signal "beforeNodePropertyChange" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect(\Neos\ContentRepository\Core\Projection\ContentGraph\Node::class, 'beforeNodePropertyChange', function () { return 'foo'; });
        // TODO 9.0 migration: The signal "nodePropertyChanged" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'nodePropertyChanged', RouterCachingService::class, 'flushCaches');
    }
}
