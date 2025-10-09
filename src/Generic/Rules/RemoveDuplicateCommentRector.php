<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveDuplicateCommentRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * 1st level: file name
     * 2nd level: comment text
     * values: start-line-numbers where comment was found (as array).
     *
     * NOTE: Duplicates are not included in the list.
     * @var array<string>
     */
    private $seenCommentsInFiles;

    public function __construct(
    )
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Warning comments for various non-supported use cases', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node::class];
    }

    /**
     * @param Node $node
     */
    public function refactor(Node $node)
    {
        if (self::commentContains90Comment($node)) {
            $filePath = $this->file->getFilePath();
            $changedComments = false;
            $newComments = [];
            foreach ($node->getComments() as $comment) {
                $lineNumbers = $this->seenCommentsInFiles[$filePath][$comment->getText()] ?? [];

                if (self::isDuplicate($comment, $lineNumbers)) {
                    $changedComments = true;

                    if ($node instanceof Node\Stmt\Nop) {
                        $node->setAttribute('comments', []);
                        return NodeVisitor::REMOVE_NODE;
                    }
                } else {
                    $newComments[] = $comment;
                }
                if (array_search($comment->getStartLine(), $lineNumbers) === false) {
                    $this->seenCommentsInFiles[$filePath][$comment->getText()][] = $comment->getStartLine();
                }
            }

            if ($changedComments) {
                $node->setAttribute('comments', $newComments);
            }
        }

        return null;
    }

    private static function isDuplicate(\PhpParser\Comment $comment, array $lineNumbers)
    {
        if (count($lineNumbers) > 0 && $comment->getStartLine() === -1) {
            return true;
        }
        foreach ($lineNumbers as $lineNumber) {
            $delta = $comment->getStartLine() - $lineNumber;
            if ($delta > 0 && $delta < 4) {
                return true;
            }
        }
        return false;
    }

    private static function commentContains90Comment(Node $node): bool
    {
        foreach ($node->getComments() as $comment) {
            if (str_contains($comment->getText(), '// TODO 9.0')) {
                return true;
            }
        }
        return false;
    }
}
