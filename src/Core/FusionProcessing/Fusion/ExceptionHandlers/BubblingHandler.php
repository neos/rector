<?php

declare(strict_types=1);

namespace Neos\Rector\Core\FusionProcessing\Fusion\ExceptionHandlers;

/*
 * This file is part of the Neos.Fusion package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Configuration\Exception\InvalidConfigurationException;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Fusion\Exception\RuntimeException;

/**
 * Wrap the exception to retain the fusion path at which it was originally thrown
 */
class BubblingHandler extends AbstractRenderingExceptionHandler
{
    /**
     * Handle an Exception thrown while rendering Fusion
     *
     * @param string $fusionPath
     * @param \Exception $exception
     * @return string
     * @throws StopActionException
     * @throws InvalidConfigurationException
     * @throws \Exception
     */
    public function handleRenderingException($fusionPath, \Exception $exception)
    {
        if ($exception instanceof RuntimeException) {
            throw $exception;
        } else {
            throw new RuntimeException('Fusion Rendering Exception, see fusionPath and nested Exception for details.', 1401803055, $exception, $fusionPath);
        }
    }

    /**
     * Handles an Exception thrown while rendering Fusion
     *
     * @param string $fusionPath path causing the exception
     * @param \Exception $exception exception to handle
     * @param string|null $referenceCode
     * @return string
     */
    protected function handle($fusionPath, \Exception $exception, $referenceCode)
    {
        // nothing to be done here, as this method is normally called in "handleRenderingException()", which was overridden above.
        throw new \BadMethodCallException('Never called.');
    }
}
