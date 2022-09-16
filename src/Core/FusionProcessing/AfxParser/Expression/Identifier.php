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
 * Class Identifier
 * @package Neos\Rector\Core\FusionProcessing\AfxParser\Expression
 */
class Identifier
{
    /**
     * @param Lexer $lexer
     * @return string
     * @throws AfxParserException
     */
    public static function parse(Lexer $lexer): string
    {
        $identifier = '';

        while (true) {
            switch (true) {
                case $lexer->isAlphaNumeric():
                case $lexer->isDot():
                case $lexer->isColon():
                case $lexer->isMinus():
                case $lexer->isUnderscore():
                case $lexer->isAt():
                case $lexer->isDoubleQuote():
                case $lexer->isSingleQuote():
                case $lexer->isBackSlash() && $lexer->peek(2) === '\"':
                case $lexer->isBackSlash() && $lexer->peek(2) === '\\\'':
                    $identifier .= $lexer->consume();
                    break;
                case $lexer->isEqualSign():
                case $lexer->isWhiteSpace():
                case $lexer->isClosingBracket():
                case $lexer->isForwardSlash():
                    return $identifier;
                    break;
                default:
                    $unexpected_character = $lexer->consume();
                    throw new AfxParserException(sprintf(
                        'Unexpected character "%s" in identifier "%s"',
                        $unexpected_character,
                        $identifier
                    ), 1557860650);
            }
        }
    }
}
