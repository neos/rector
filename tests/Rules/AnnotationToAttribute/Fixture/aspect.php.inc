<?php

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Aop\PointcutFilterInterface;

/**
 * @Flow\Aspect
 */
class SomeAspect
{
    /**
     * @Flow\Around("method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())")
     */
    public function around(JoinPointInterface $joinPoint)
    {
    }
    /**
     * @Flow\Before("method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())")
     */
    public function before(JoinPointInterface $joinPoint)
    {
    }
    /**
     * @Flow\AfterReturning("method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())")
     */
    public function afterReturning(JoinPointInterface $joinPoint)
    {
    }
    /**
     * @Flow\AfterThrowing("method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())")
     */
    public function afterThrowing(JoinPointInterface $joinPoint)
    {
    }
    /**
     * @Flow\After("method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())")
     */
    public function after(JoinPointInterface $joinPoint)
    {
    }
    /**
     * @Flow\Pointcut("method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())")
     */
    public function pointcut(PointcutFilterInterface $joinPoint)
    {
    }
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Aop\PointcutFilterInterface;

#[Flow\Aspect]
class SomeAspect
{
    #[Flow\Around('method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())')]
    public function around(JoinPointInterface $joinPoint)
    {
    }
    #[Flow\Before('method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())')]
    public function before(JoinPointInterface $joinPoint)
    {
    }
    #[Flow\AfterReturning('method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())')]
    public function afterReturning(JoinPointInterface $joinPoint)
    {
    }
    #[Flow\AfterThrowing('method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())')]
    public function afterThrowing(JoinPointInterface $joinPoint)
    {
    }
    #[Flow\After('method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())')]
    public function after(JoinPointInterface $joinPoint)
    {
    }
    #[Flow\Pointcut('method(Neos\Flow\ResourceManagement\ResourceManager->getPublicPackageResourceUri())')]
    public function pointcut(PointcutFilterInterface $joinPoint)
    {
    }
}

?>
