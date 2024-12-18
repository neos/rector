<?php

namespace Neos\Rector\Test;

use Neos\Neos\Routing\FrontendNodeRoutePartHandlerInterface;

class SomeClass implements FrontendNodeRoutePartHandlerInterface
{

}

-----
<?php

namespace Neos\Rector\Test;

use Neos\Neos\Routing\FrontendNodeRoutePartHandlerInterface;

class SomeClass implements \Neos\Neos\FrontendRouting\FrontendNodeRoutePartHandlerInterface
{

}

