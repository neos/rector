<?php

class SomeClass
{
    /**
     * @var \Neos\ContentRepository\Core\NodeType\NodeTypeManager
     * @Flow\Inject
     */
    protected $nodeTypeManager;
    public function run()
    {
        $nt = $this->nodeTypeManager->getNodeTypes(false);
    }
}

?>
-----
<?php

class SomeClass
{
    /**
     * @var \Neos\ContentRepository\Core\NodeType\NodeTypeManager
     * @Flow\Inject
     */
    protected $nodeTypeManager;
    public function run()
    {
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        $nt = $contentRepository->getNodeTypeManager()->getNodeTypes(false);
    }
}

?>
