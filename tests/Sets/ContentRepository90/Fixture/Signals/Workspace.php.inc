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

        $dispatcher->connect(\Neos\ContentRepository\Domain\Model\Workspace::class, 'baseWorkspaceChanged', function () { return 'foo'; });
        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Workspace', 'beforeNodePublishing', RouterCachingService::class, 'flushCaches');
        $dispatcher->connect(\Neos\ContentRepository\Domain\Model\Workspace::class, 'afterNodePublishing', function () { return 'foo'; });
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
        // TODO 9.0 migration: The signal "baseWorkspaceChanged" on "Workspace" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.


        $dispatcher->connect(\Neos\ContentRepository\Core\SharedModel\Workspace\Workspace::class, 'baseWorkspaceChanged', function () { return 'foo'; });
        // TODO 9.0 migration: The signal "beforeNodePublishing" on "Workspace" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.

        $dispatcher->connect('Neos\ContentRepository\Domain\Model\Workspace', 'beforeNodePublishing', RouterCachingService::class, 'flushCaches');
        // TODO 9.0 migration: The signal "afterNodePublishing" on "Workspace" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.

        $dispatcher->connect(\Neos\ContentRepository\Core\SharedModel\Workspace\Workspace::class, 'afterNodePublishing', function () { return 'foo'; });
    }
}
