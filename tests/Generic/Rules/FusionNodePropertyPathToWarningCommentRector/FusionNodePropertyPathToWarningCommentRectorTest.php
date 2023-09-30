<?php

declare(strict_types=1);

namespace Neos\Rector\Tests\Generic\Rules\FusionNodePropertyPathToWarningCommentRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class FusionNodePropertyPathToWarningCommentRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture', '*.fusion.inc');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
