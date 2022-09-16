<?php

declare(strict_types=1);

namespace Neos\Rector\Core\FusionProcessing;

use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Parallel\ValueObject\Bridge;

class FusionFileProcessor implements FileProcessorInterface
{

    /**
     * @param FusionRectorInterface[] $fusionRectors
     */
    public function __construct(private readonly array $fusionRectors, private readonly FileDiffFactory $fileDiffFactory)
    {
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        return str_ends_with($file->getFilePath(), '.fusion');
    }

    public function process(File $file, Configuration $configuration): array
    {
        $systemErrorsAndFileDiffs = [Bridge::SYSTEM_ERRORS => [], Bridge::FILE_DIFFS => []];
        if ($this->fusionRectors === []) {
            return $systemErrorsAndFileDiffs;
        }
        $oldFileContent = $file->getFileContent();
        $newFileContent = $file->getFileContent();
        foreach ($this->fusionRectors as $fusionRector) {
            $newFileContent = $fusionRector->refactorFileContent($file->getFileContent());
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
        return ['fusion'];
    }
}
