<?php
declare (strict_types=1);

namespace Neos\Rector\Generic\Rules\Traits;

use PhpParser\Comment;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;

trait FunctionsTrait
{
    /**
     * @var \Rector\Core\PhpParser\Node\NodeFactory
     */
    protected $nodeFactory;

    private function iteratorToArray(Expr $inner): Expr
    {
        return $this->nodeFactory->createFuncCall('iterator_to_array', [$inner]);
    }

    private function castToString(Expr $inner): Expr
    {
        return new Expr\Cast\String_($inner);
    }

    private static function assign(string $variableName, Expr $value): Expression
    {
        // NOTE: it is crucial to wrap thw assign in an expression; so that this works with self::withTodoComment.
        // (otherwise the comment is silently swallowed because it is added to the wrong element, and thus not printed)
        return new Expression(
            new Assign(
                new Variable($variableName),
                $value
            )
        );
    }

    private static function todoComment(string $commentText): Nop
    {
        return new Nop([
            'comments' => [
                new Comment('// TODO 9.0 migration: ' . $commentText)
            ]
        ]);
    }

    private static function withTodoComment(string $commentText, \PhpParser\NodeAbstract $attachmentNode): \PhpParser\Node
    {
        $attachmentNode->setAttribute('comments', [
            new Comment('// TODO 9.0 migration: ' . $commentText)
        ]);
        return $attachmentNode;
    }

    private static function todoCommentAttribute(string $commentText): Comment
    {
        return new Comment('// TODO 9.0 migration: ' . $commentText);
    }
}
