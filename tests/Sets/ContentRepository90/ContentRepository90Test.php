<?php

declare(strict_types=1);

namespace Neos\Rector\Tests\Sets\ContentRepository90;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ContentRepository90Test extends AbstractRectorTestCase
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
        $append = new \AppendIterator();
        $append->append($this->yieldFilesFromDirectory(__DIR__ . '/Fixture'));
        $append->append($this->yieldFilesFromDirectory(__DIR__ . '/Fixture', '*.fusion.inc'));
        return $append;
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/../../../config/set/contentrepository-90.php';
    }
}
