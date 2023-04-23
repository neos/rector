<?php

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\YamlProcessing\YamlRectorInterface;
use Neos\Rector\Core\YamlProcessing\YamlWithComments;
use Neos\Rector\Utility\CodeSampleLoader;
use Neos\Utility\Arrays;
use Symfony\Component\Yaml\Yaml;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class YamlDimensionConfigRector implements YamlRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite Settings.yaml config to new language', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        $parsed = Yaml::parse($fileContent);
        if (isset($parsed['Neos']['ContentRepositoryRegistry']['contentRepositories']['default']['contentDimensions'])) {
            // we already have a Neos.ContentRepositoryRegistry.contentRepositories.default.contentDimensions key
            // -> we assume the file has already been processed.
            return $fileContent;
        }

        if (!isset($parsed['Neos']['ContentRepository']['contentDimensions'])) {
            // we do not have a Neos.ContentRepository.contentDimensions key; so we do not need
            // to process this file
            return $fileContent;
        }

        $defaultDimensionSpacePoint = [];
        $uriPathSegments = [];
        foreach ($parsed['Neos']['ContentRepository']['contentDimensions'] as $dimensionName => $oldDimensionConfig) {
            $errors = [];
            $uriPathSegmentsForDimension = [
                'dimensionIdentifier' => $dimensionName,
                'dimensionValueMapping##' => YamlWithComments::comment('dimensionValue => uriPathSegment (empty uriPathSegment allowed)'),
                'dimensionValueMapping' => []
            ];
            $newContentDimensionConfig = [];

            if (isset($oldDimensionConfig['label'])) {
                $newContentDimensionConfig['label'] = $oldDimensionConfig['label'];
            }
            if (isset($oldDimensionConfig['icon'])) {
                $newContentDimensionConfig['icon'] = $oldDimensionConfig['icon'];
            }

            if (isset($oldDimensionConfig['default'])) {
                $defaultDimensionSpacePoint[$dimensionName] = $oldDimensionConfig['default'];
            } else {
                $errors[] = sprintf('TODO: FIXME: For preset "%s", did not find any default dimension value underneath "default". The defaultDimensionSpacePoint might be incomplete.', $presetName);
            }
            foreach ($oldDimensionConfig['presets'] as $presetName => $presetConfig) {
                // we need to use the last dimension value as the the new dimension value name; because that is
                // what the dimension migrator expects.
                //
                // The PresetName is discarded
                // TODO: PresetName as comment
                $dimensionValueConfig = [];
                if (isset($presetConfig['label'])) {
                    $dimensionValueConfig['label'] = $presetConfig['label'];
                }
                if (isset($presetConfig['icon'])) {
                    $dimensionValueConfig['icon'] = $presetConfig['icon'];
                }


                if (!isset($presetConfig['values'])) {
                    $errors[] = sprintf('TODO: FIXME: For preset "%s", did not find any dimension values underneath "values"', $presetName);
                } else {
                    $valuesExceptLast = $presetConfig['values'];
                    $valuesExceptLast = array_reverse($valuesExceptLast);
                    $lastValue = array_pop($valuesExceptLast);
                    $currentValuePath = &$newContentDimensionConfig['values'];
                    foreach ($valuesExceptLast as $value) {
                        $currentValuePath = &$currentValuePath[$value]['specializations'];
                    }
                    $currentValuePath[$lastValue] = $dimensionValueConfig;

                    if (isset($presetConfig['uriSegment'])) {
                        $uriPathSegmentsForDimension['dimensionValueMapping'][$lastValue] = $presetConfig['uriSegment'];
                    } else {
                        $errors[] = sprintf('TODO: FIXME: For preset "%s", did not find any uriSegment.', $presetName);
                    }
                }
            }

            if ($errors) {
                $parsed['Neos']['ContentRepositoryRegistry']['contentRepositories']['default']['contentDimensions'][$dimensionName . '##'] = YamlWithComments::comment(implode("\n", $errors));
            }
            $parsed['Neos']['ContentRepositoryRegistry']['contentRepositories']['default']['contentDimensions'][$dimensionName] = $newContentDimensionConfig;
            $uriPathSegments[] = $uriPathSegmentsForDimension;
        }
        $parsed['Neos']['ContentRepository']['contentDimensions'] = [];
        $parsed = Arrays::removeEmptyElementsRecursively($parsed);

        $parsed['Neos']['Neos']['sites']['*']['contentDimensions'] = [
            'defaultDimensionSpacePoint##' => YamlWithComments::comment('defaultDimensionSpacePoint is used for the homepage (URL /)'),
            'defaultDimensionSpacePoint' => $defaultDimensionSpacePoint,
            'resolver' => [
                'factoryClassName' => 'Neos\Neos\FrontendRouting\DimensionResolution\Resolver\UriPathResolverFactory',
                'options' => [
                    'segments' => $uriPathSegments
                ]
            ]
        ];

        return YamlWithComments::dump($parsed);
    }
}
