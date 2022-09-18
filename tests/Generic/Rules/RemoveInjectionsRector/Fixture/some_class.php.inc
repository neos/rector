<?php

use \Foo\Bar\Baz;
use Neos\Flow\Annotations as Flow;

class SomeClass
{
  /**
   * @Flow\Inject
   * @var \Foo\Bar\Baz
   */
  protected $foo = null;

  /**
   * @var Baz;
   */
  protected $foo2 = null;

  /**
   * @Flow\Inject
   * @var Baz;
   */
  protected $foo3 = null;

  protected \Foo\Bar\Baz $foo4;

  #[Flow\Inject]
  protected \Foo\Bar\Baz $foo5;

  #[Flow\Inject]
  protected Baz $foo6;
}

?>
-----
<?php

use \Foo\Bar\Baz;
use Neos\Flow\Annotations as Flow;

class SomeClass
{
  /**
   * @var Baz;
   */
  protected $foo2 = null;

  protected \Foo\Bar\Baz $foo4;
}

?>
