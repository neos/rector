<?php

declare(strict_types=1);

namespace Neos\Rector\Tests\ContentRepository90\Rules\FusionContextLiveRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class FusionContextLiveRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture', '*.fusion');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
