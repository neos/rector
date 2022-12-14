<?php
declare(strict_types=1);

namespace Neos\Rector\Core\FusionProcessing\AfxParser\Expression;

/*
 * This file is part of the Neos.Fusion.Afx package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Rector\Core\FusionProcessing\AfxParser\AfxParserException;
use Neos\Rector\Core\FusionProcessing\AfxParser\Lexer;

/**
 * Class Expression
 * @package Neos\Rector\Core\FusionProcessing\AfxParser\Expression
 */
class Comment
{
    /**
     * @param Lexer $lexer
     * @return string
     * @throws AfxParserException
     */
    public static function parse(Lexer $lexer)
    {
        if ($lexer->isOpeningBracket() && $lexer->peek(4) === '<!--') {
            $lexer->consume();
            $lexer->consume();
            $lexer->consume();
            $lexer->consume();
        } else {
            throw new AfxParserException(sprintf('Unexpected comment start'));
        }
        $currentComment = '';
        while (true) {
            if ($lexer->isMinus() && $lexer->peek(3) === '-->') {
                $lexer->consume();
                $lexer->consume();
                $lexer->consume();
                return $currentComment;
            }
            if ($lexer->isEnd()) {
                throw new AfxParserException(sprintf('Comment not closed.'));
            }
            $currentComment .= $lexer->consume();
        }
    }
}
