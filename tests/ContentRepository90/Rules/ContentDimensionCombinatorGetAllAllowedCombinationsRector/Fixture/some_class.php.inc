<?php

use Neos\ContentRepository\Domain\Service\ContentDimensionCombinator;

class SomeClass
{
    /**
     * @var ContentDimensionCombinator
     * @Flow\Inject
     */
    protected $contentDimensionCombinator;
    public function run()
    {
        $combinations = $this->contentDimensionCombinator->getAllAllowedCombinations();
        foreach ($combinations as $combination) {
        }
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Service\ContentDimensionCombinator;

class SomeClass
{
    /**
     * @var ContentDimensionCombinator
     * @Flow\Inject
     */
    protected $contentDimensionCombinator;
    public function run()
    {
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        $dimensionSpacePoints = $contentRepository->getVariationGraph()->getDimensionSpacePoints();
        // TODO 9.0 migration: try to directly work with $dimensionSpacePoints, instead of converting them to the legacy dimension format
        $combinations = array_map(fn(\Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePoint $dimensionSpacePoint) => $dimensionSpacePoint->toLegacyDimensionArray(), iterator_to_array($dimensionSpacePoints));
        foreach ($combinations as $combination) {
        }
    }
}

?>
