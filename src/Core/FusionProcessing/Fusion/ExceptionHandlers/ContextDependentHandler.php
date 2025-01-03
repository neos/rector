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

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Utility\Environment;

/**
 * A special exception handler that is used on the outer path to catch all unhandled exceptions and uses other exception
 * handlers depending on the context.
 */
class ContextDependentHandler extends AbstractRenderingExceptionHandler
{
    /**
     * @Flow\Inject
     * @var Environment
     */
    protected $environment;

    /**
     * Handle an exception depending on the context with an HTML message or XML comment
     *
     * @param string $fusionPath path causing the exception
     * @param \Exception $exception exception to handle
     * @param string|null $referenceCode
     * @return string
     */
    protected function handle($fusionPath, \Exception $exception, $referenceCode)
    {
        $context = $this->environment->getContext();
        if ($context->isDevelopment()) {
            $handler = new HtmlMessageHandler();
        } else {
            $handler = new XmlCommentHandler();
        }
        $handler->setRuntime($this->getRuntime());
        return $handler->handleRenderingException($fusionPath, $exception);
    }
}
