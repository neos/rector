<?php

use My\Class\To\Comment;
use Neos\Flow\Package\Package as BasePackage;

class Package extends BasePackage
{
    public function boot()
    {
        $myComment = new Comment();
    }
}

?>
-----
<?php

use My\Class\To\Comment;
use Neos\Flow\Package\Package as BasePackage;

class Package extends BasePackage
{
    public function boot()
    {
        // TODO 9.0 migration: This is a comment
        $myComment = new Comment();
    }
}

?>
