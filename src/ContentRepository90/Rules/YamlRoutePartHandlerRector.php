<?php

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\YamlProcessing\YamlRectorInterface;
use Neos\Rector\Core\YamlProcessing\YamlWithComments;
use Neos\Rector\Utility\CodeSampleLoader;
use Neos\Utility\Arrays;
use Symfony\Component\Yaml\Yaml;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class YamlRoutePartHandlerRector implements YamlRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite Routes.yaml config to use Neos\Neos\FrontendRouting\FrontendNodeRoutePartHandlerInterface as route part handler', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        $parsed = Yaml::parse($fileContent);
        if (!is_array($parsed)) {
            return $fileContent;
        }

        foreach ($parsed as $routeConfigKey => $routeConfig) {
            if (!is_array($routeConfig)) {
                continue;
            }
            if (!isset($routeConfig['routeParts']) || !is_array($routeConfig['routeParts'])) {
                continue;
            }

            foreach ($routeConfig['routeParts'] as $routePartKey => $routePart) {
                if (isset($routePart['handler']) && $routePart['handler'] === 'Neos\Neos\Routing\FrontendNodeRoutePartHandler') {
                    $parsed[$routeConfigKey]['routeParts'][$routePartKey]['handler'] = 'Neos\Neos\FrontendRouting\FrontendNodeRoutePartHandlerInterface';
                }
            }
        }

        return YamlWithComments::dump($parsed);
    }
}
