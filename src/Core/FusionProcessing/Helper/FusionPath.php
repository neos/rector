<?php

namespace Neos\Rector\Core\FusionProcessing\Helper;

final class FusionPath
{
    private function __construct(
        private readonly array $fusionPath
    ) {
    }

    public static function createEmpty(): self
    {
        return new self([]);
    }

    public static function create(?array $currentPath): self
    {
        if (!$currentPath) {
            return self::createEmpty();
        }
        return new self($currentPath);
    }

    public function containsSegments(string ...$expectedSegments): bool
    {
        // f.e. count($this->fusionPath) === 6
        // f.e. count($expectedSegments) === 3
        // $this->fusionPath 0 | 1 | 2 | 3 | 4 | 5
        // $expectedSegments ----------
        // $expectedSegments    -----------
        // $expectedSegments        -----------
        // $expectedSegments            ----------
        for ($startOffset = 0; $startOffset <= count($this->fusionPath) - count($expectedSegments); $startOffset++) {
            if (array_slice($this->fusionPath, $startOffset, count($expectedSegments)) === $expectedSegments) {
                return true;
            }
        }

        return false;
    }
}
