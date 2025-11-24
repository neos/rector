<?php

declare(strict_types=1);

namespace Neos\Rector\Utility;

use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class CodeSampleLoader
{
    static function fromFile(string $description, string $rectorClassName, array $codeSampleConfiguration = []): RuleDefinition
    {
        $shortName = (new \ReflectionClass($rectorClassName))->getShortName();
        if (str_contains($rectorClassName, 'ContentRepository90')) {
            $folderName = __DIR__ . '/../../tests/ContentRepository90/Rules/' . $shortName . '/Fixture/';
        } elseif (str_contains($rectorClassName, 'Generic')) {
            $folderName = __DIR__ . '/../../tests/Generic/Rules/' . $shortName . '/Fixture/';
        } else {
            $folderName = __DIR__ . '/../../tests/Rules/' . $shortName . '/Fixture/';
        }

        if (!file_exists($folderName)) {
            // we did not move all tests to the new location yet
            $folderName = __DIR__ . '/../../tests/Rules/' . $shortName . '/Fixture/';
        }
        $files = glob($folderName . '*.inc');
        $file = reset($files);
        [$beforeCode, $afterCode] = explode('-----', file_get_contents($file));
        if (!empty($codeSampleConfiguration)) {
            $codeSample = new ConfiguredCodeSample(trim($beforeCode), trim($afterCode), $codeSampleConfiguration);
        } else {
            $codeSample = new CodeSample(trim($beforeCode), trim($afterCode));
        }
        return new RuleDefinition($description, [$codeSample]);
    }
}
