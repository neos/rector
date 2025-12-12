<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $dimension = $node->getDimensions()[0];

        $dimensions = MyFoo::do($node->getDimensions());

        return $node->getDimensions();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        // TODO 9.0 migration: Try to remove the toLegacyDimensionArray() call and make your codebase more typesafe.
        $dimension = $node->originDimensionSpacePoint->toLegacyDimensionArray()[0];

        // TODO 9.0 migration: Try to remove the toLegacyDimensionArray() call and make your codebase more typesafe.
        $dimensions = MyFoo::do($node->originDimensionSpacePoint->toLegacyDimensionArray());

        // TODO 9.0 migration: Try to remove the toLegacyDimensionArray() call and make your codebase more typesafe.
        return $node->originDimensionSpacePoint->toLegacyDimensionArray();
    }
}

?>
