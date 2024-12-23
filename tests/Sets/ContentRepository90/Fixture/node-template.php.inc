<?php

use Neos\ContentRepository\Domain\Model\NodeTemplate;

class SomeClass {

    public function foo()
    {
        $nodeTemplate = new NodeTemplate();
        $nodeTemplate->getIdentifier();
        $nodeTemplate->getName();
        $nodeTemplate->getWorkspace();
        $nodeTemplate->setIdentifier();
        $nodeTemplate->setName();
    }
}
-----
<?php

use Neos\ContentRepository\Domain\Model\NodeTemplate;

class SomeClass {

    public function foo()
    {
        // TODO 9.0 migration: !! NodeTemplate is removed in Neos 9.0. Use the "CreateNodeAggregateWithNode" command to create new nodes or "CreateNodeVariant" command to create variants of an existing node in other dimensions.

        $nodeTemplate = new NodeTemplate();
        // TODO 9.0 migration: !! NodeTemplate::getIdentifier is removed in Neos 9.0. Use the "CreateNodeAggregateWithNode" command to create new nodes or "CreateNodeVariant" command to create variants of an existing node in other dimensions.

        $nodeTemplate->getIdentifier();
        // TODO 9.0 migration: !! NodeTemplate::getName is removed in Neos 9.0. Use the "CreateNodeAggregateWithNode" command to create new nodes or "CreateNodeVariant" command to create variants of an existing node in other dimensions.

        $nodeTemplate->getName();
        // TODO 9.0 migration: !! NodeTemplate::getWorkspace is removed in Neos 9.0. Use the "CreateNodeAggregateWithNode" command to create new nodes or "CreateNodeVariant" command to create variants of an existing node in other dimensions.

        $nodeTemplate->getWorkspace();
        // TODO 9.0 migration: !! NodeTemplate::setIdentifier is removed in Neos 9.0. Use the "CreateNodeAggregateWithNode" command to create new nodes or "CreateNodeVariant" command to create variants of an existing node in other dimensions.

        $nodeTemplate->setIdentifier();
        // TODO 9.0 migration: !! NodeTemplate::setName is removed in Neos 9.0. Use the "CreateNodeAggregateWithNode" command to create new nodes or "CreateNodeVariant" command to create variants of an existing node in other dimensions.

        $nodeTemplate->setName();
    }
}
