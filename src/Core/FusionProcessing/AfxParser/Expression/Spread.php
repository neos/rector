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
 * Class Spread
 * @package Neos\Rector\Core\FusionProcessing\AfxParser\Expression
 */
class Spread
{
    /**
     * @param Lexer $lexer
     * @return array
     * @throws AfxParserException
     */
    public static function parse(Lexer $lexer): array
    {
        $contents = '';
        $braceCount = 0;

        if ($lexer->isOpeningBrace() && $lexer->peek(4) === '{...') {
            $lexer->consume();
            $lexer->consume();
            $lexer->consume();
            $lexer->consume();
        } else {
            throw new AfxParserException('Spread without braces', 1557860522);
        }

        $fromOffset = $lexer->characterPosition;
        while (true) {
            if ($lexer->isEnd()) {
                throw new AfxParserException(sprintf('Unfinished Spread "%s"', $contents), 1557860526);
            }

            if ($lexer->isOpeningBrace()) {
                $braceCount++;
            }

            if ($lexer->isClosingBrace()) {
                if ($braceCount === 0) {
                    $toOffset = $lexer->characterPosition;
                    $lexer->consume();
                    return [
                        'type' => 'expression',
                        'payload' => [
                            'from' => $fromOffset,
                            'to' => $toOffset,
                            'contents' => $contents
                        ]
                    ];
                }

                $braceCount--;
            }

            $contents .= $lexer->consume();
        }
    }
}
