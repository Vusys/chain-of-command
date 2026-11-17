<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Smarty\Plugins\HighlightFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HighlightFunctionTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string, string}>
     */
    public static function cases(): iterable
    {
        yield 'no term: escaped text' => ['Ada & co', '', 'Ada &amp; co'];
        yield 'every match, any case, no overlaps' => ['Ana banana', 'ana', '<mark>Ana</mark> b<mark>ana</mark>na'];
        yield 'markup in the text is escaped' => ['<b>Ana</b>', 'ana', '&lt;b&gt;<mark>Ana</mark>&lt;/b&gt;'];
        yield 'markup in the term is matched literally' => ['a<b>c', '<b>', 'a<mark>&lt;b&gt;</mark>c'];
        yield 'regex characters are literal' => ['1+1=2', '+', '1<mark>+</mark>1=2'];
        yield 'multibyte' => ['Zoë Ünal', 'ün', 'Zoë <mark>Ün</mark>al'];
        yield 'no match' => ['Ada', 'zed', 'Ada'];
    }

    #[DataProvider('cases')]
    public function test_it_marks_matches_and_escapes_everything(string $text, string $term, string $expected): void
    {
        $this->assertSame($expected, (new HighlightFunction)(['text' => $text, 'term' => $term]));
    }
}
