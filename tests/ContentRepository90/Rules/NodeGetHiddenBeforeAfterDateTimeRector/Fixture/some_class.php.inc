<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function nodeHiddenBeforeDateTime(NodeLegacyStub $node)
    {
        $dateTime = $node->getHiddenBeforeDateTime();

        $node->setHiddenBeforeDateTime($dateTime);
    }

    public function nodeHiddenAfterDateTime(NodeLegacyStub $node)
    {
        $dateTime = $node->getHiddenAfterDateTime();

        $node->setHiddenAfterDateTime($dateTime);
    }
}

?>
-----
<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function nodeHiddenBeforeDateTime(NodeLegacyStub $node)
    {
        // TODO 9.0 migration: Timed publishing has been conceptually changed and has been extracted into a dedicated package. Please check https://github.com/neos/timeable-node-visibility for further details.

        $dateTime = $node->getProperty('enableAfterDateTime');
        // TODO 9.0 migration: Timed publishing has been conceptually changed and has been extracted into a dedicated package. Please check https://github.com/neos/timeable-node-visibility for further details.
        // Use the "SetNodeProperties" command to change property values for "enableAfterDateTime" or "disableAfterDateTime".


        $node->setHiddenBeforeDateTime($dateTime);
    }

    public function nodeHiddenAfterDateTime(NodeLegacyStub $node)
    {
        // TODO 9.0 migration: Timed publishing has been conceptually changed and has been extracted into a dedicated package. Please check https://github.com/neos/timeable-node-visibility for further details.

        $dateTime = $node->getProperty('disableAfterDateTime');
        // TODO 9.0 migration: Timed publishing has been conceptually changed and has been extracted into a dedicated package. Please check https://github.com/neos/timeable-node-visibility for further details.
        // Use the "SetNodeProperties" command to change property values for "enableAfterDateTime" or "disableAfterDateTime".


        $node->setHiddenAfterDateTime($dateTime);
    }
}

?>
