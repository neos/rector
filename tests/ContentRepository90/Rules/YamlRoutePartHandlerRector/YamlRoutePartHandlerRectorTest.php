<?php

declare(strict_types=1);

namespace Neos\Rector\Tests\ContentRepository90\Rules\YamlDimensionConfigRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class YamlRoutePartHandlerRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $fileInfo): void
    {
        $this->doTestFile($fileInfo);
    }

    /**
     * @return \Iterator<string>
     */
    public function provideData(): \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture', '*.yaml.inc');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
