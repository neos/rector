<?php

namespace Neos\Rector\Test;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Service\Context;

class SomeClass extends AnotherClass
{
    /**
     * @Flow\Inject
     * @var \Neos\Neos\KeepThis
     */
    private $keepThisBecauseItDoesNotNeedToGetRemoved;

    /**
     * @var \Neos\Neos\Domain\Service\NodeSearchServiceInterface
     */
    private $keepThisBecauseNoInject;

    /**
     * @Flow\Inject
     * @var \Neos\Neos\Domain\Service\NodeSearchServiceInterface
     */
    private $nodeSearchServiceInterface;

    /**
     * @Flow\Inject
     * @var \Neos\Neos\Domain\Service\NodeSearchService
     */
    private $nodeSearchService;

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Service\NodeTypeManager
     */
    protected $nodeTypeManagerNew;

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Core\NodeType\NodeTypeManager
     */
    protected $nodeTypeManagerOld;

    /**
     * @Flow\Inject
     * @var \Neos\Neos\Domain\Service\ContentContextFactory
     */
    protected $contentContextFactory;

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Service\ContextFactoryInterface
     */
    protected $contentRepositoryContextFactoryInterface;

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Service\ContextFactory
     */
    protected $contentRepositoryContextFactory;

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Service\Context
     */
    protected $context;

    /**
     * @Flow\Inject
     * @var \Neos\Neos\Domain\Service\ContentContext
     */
    protected $contentContext;

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Repository\NodeDataRepository
     */
    protected $nodeDataRepository;

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Repository\WorkspaceRepository
     */
    protected $workspaceRepository;
    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Service\ContentDimensionCombinator
     */
    protected $contentDimensionCombinator;
    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Factory\NodeFactory
     */
    protected $nodeFactory;
}

-----
<?php

namespace Neos\Rector\Test;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Service\Context;

class SomeClass extends AnotherClass
{
    /**
     * @Flow\Inject
     * @var \Neos\Neos\KeepThis
     */
    private $keepThisBecauseItDoesNotNeedToGetRemoved;

    /**
     * @var \Neos\Neos\Domain\Service\NodeSearchServiceInterface
     */
    private $keepThisBecauseNoInject;
}

