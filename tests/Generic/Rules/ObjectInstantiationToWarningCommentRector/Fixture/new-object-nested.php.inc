<?php

use My\Class\To\Comment;
use Neos\Flow\Package\Package as BasePackage;

class Package extends BasePackage
{
    public function boot()
    {
        $author = (new Comment(['author']))->getAutor();
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

        $author = (new Comment(['author']))->getAutor();
    }
}

?>
