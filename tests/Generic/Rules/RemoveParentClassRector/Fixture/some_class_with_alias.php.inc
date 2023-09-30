<?php
use \Foo\Bar\Baz;
class SomeClass extends Baz
{
}

?>
-----
<?php
use \Foo\Bar\Baz;
// TODO: Neos 9.0 Migration: Stuff
class SomeClass
{
}

?>
