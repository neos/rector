<?php

declare(strict_types=1);

namespace Neos\Rector\Core\YamlProcessing;

use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Parallel\ValueObject\Bridge;

class YamlFileProcessor implements FileProcessorInterface
{

    /**
     * @param YamlRectorInterface[] $yamlRectors
     */
    public function __construct(private readonly array $yamlRectors, private readonly FileDiffFactory $fileDiffFactory)
    {
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        return str_ends_with($file->getFilePath(), '.yaml');
    }

    public function process(File $file, Configuration $configuration): array
    {
        $systemErrorsAndFileDiffs = [Bridge::SYSTEM_ERRORS => [], Bridge::FILE_DIFFS => []];
        if ($this->yamlRectors === []) {
            return $systemErrorsAndFileDiffs;
        }
        $oldFileContent = $file->getFileContent();
        $newFileContent = $file->getFileContent();
        foreach ($this->yamlRectors as $yamlRector) {
            $newFileContent = $yamlRector->refactorFileContent($file->getFileContent());
            if ($oldFileContent === $newFileContent) {
                continue;
            }
            $file->changeFileContent($newFileContent);
        }
        if ($oldFileContent !== $newFileContent) {
            $fileDiff = $this->fileDiffFactory->createFileDiff($file, $oldFileContent, $newFileContent);
            $systemErrorsAndFileDiffs[Bridge::FILE_DIFFS][] = $fileDiff;
        }
        return $systemErrorsAndFileDiffs;
    }

    public function getSupportedFileExtensions(): array
    {
        return ['yaml'];
    }
}
