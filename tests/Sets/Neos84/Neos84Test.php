<?php

namespace Neos\Rector\Tests\Sets\Neos84;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class Neos84Test extends AbstractRectorTestCase
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
        return __DIR__ . '/../../../config/set/neos-84.php';
    }
}
