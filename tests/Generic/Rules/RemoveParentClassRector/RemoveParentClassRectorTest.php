<?php

declare(strict_types=1);

namespace Neos\Rector\Tests\Rules\RemoveParentClassRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveParentClassRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
