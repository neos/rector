<?php

use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

class SomeClass
{
    public function run(Workspace $workspace)
    {
        $workspaceName = $workspace->getName();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

class SomeClass
{
    public function run(Workspace $workspace)
    {
        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead.

        $workspaceName = $workspace->workspaceName->value;
    }
}

?>
