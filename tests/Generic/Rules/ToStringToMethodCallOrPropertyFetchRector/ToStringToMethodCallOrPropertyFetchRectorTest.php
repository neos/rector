<?php

declare(strict_types=1);

namespace Neos\Rector\Tests\Generic\Rules\ToStringToMethodCallOrPropertyFetchRector;

use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ToStringToMethodCallOrPropertyFetchRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $fileInfo): void
    {
        $this->doTestFile($fileInfo);
    }

    /**
     * @return \Iterator<string>
     */
    public static function provideData(): \Iterator
    {
        return static::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
