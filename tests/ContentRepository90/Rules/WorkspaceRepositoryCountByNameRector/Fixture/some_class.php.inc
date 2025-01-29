<?php

use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @var WorkspaceRepository
     * @Flow\Inject
     */
    protected $workspaceRepository;
    public function run(string $workspace)
    {
        return $this->workspaceRepository->countByName($workspace);
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
use Neos\Flow\Annotations as Flow;

class SomeClass
{
    /**
     * @var WorkspaceRepository
     * @Flow\Inject
     */
    protected $workspaceRepository;
    public function run(string $workspace)
    {
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        // TODO 9.0 migration: remove ternary operator (...? 1 : 0 ) - unnecessary complexity

        return $contentRepository->findWorkspaceByName(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($workspace)) !== null ? 1 : 0;
    }
}

?>
