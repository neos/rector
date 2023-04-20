<?php

namespace Neos\Rector\Tests\Core\YamlProcessing;

use Neos\Rector\Core\YamlProcessing\YamlWithComments;
use PHPUnit\Framework\TestCase;

class YamlWithCommentsTest extends TestCase
{

    /**
     * @test
     */
    public function yamlGenerationWithCommentsAndStringKeys()
    {
        $x = [
            'Neos.Neos:Foo' => [
                'myValue' => 'foo',
                'isAbstract' => true,
                'isAbstract##' => YamlWithComments::comment( 'foo'),
                'myValue2' => 'bla',
            ],
            'Neos.Neos:Foo##' => YamlWithComments::comment("My commentMy commentMy\ncommentMy commentMy commentMy commentMy commentMy commentMy commentMy\ncommentMy commentMy commentMy commentMy commentMy commentMy commentMy commentMy commentMy comment"),
        ];

        $expected = <<<EOF
# My commentMy commentMy
# commentMy commentMy commentMy commentMy commentMy commentMy commentMy
# commentMy commentMy commentMy commentMy commentMy commentMy commentMy commentMy commentMy comment
'Neos.Neos:Foo':
  myValue: foo
  # foo
  isAbstract: true
  myValue2: bla
EOF;

        $this->assertEquals(trim($expected), trim(YamlWithComments::dump($x)));
    }

    /**
     * @test
     */
    public function yamlGenerationWithCommentsAndNumberKeys()
    {
        $x = [
            'Neos.Neos:Foo' => [
                'volumes' => [
                    'a',
                    'b',
                    YamlWithComments::comment('Mein Kommentar'),
                    'c'
                ],
            ],
        ];

        $expected = <<<EOF
'Neos.Neos:Foo':
  volumes:
    - a
    - b
    # Mein Kommentar
    - c
EOF;

        $this->assertEquals(trim($expected), trim(YamlWithComments::dump($x)));
    }
}
