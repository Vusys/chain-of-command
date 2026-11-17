<?php

declare(strict_types=1);

namespace App\Smarty\Plugins;

/**
 * {highlight text=$name term=$q}: the text, escaped, with every
 * case-insensitive match of the term wrapped in <mark>. A function, not a
 * modifier, because a modifier's HTML would be escaped again by escape_html.
 */
class HighlightFunction
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function __invoke(array $params): string
    {
        $text = is_scalar($params['text'] ?? null) ? (string) $params['text'] : '';
        $term = is_scalar($params['term'] ?? null) ? trim((string) $params['term']) : '';

        if ($term === '') {
            return e($text);
        }

        $parts = preg_split('/('.preg_quote($term, '/').')/iu', $text, flags: PREG_SPLIT_DELIM_CAPTURE) ?: [$text];

        $html = '';
        foreach ($parts as $index => $part) {
            // odd indexes are the captured matches
            $html .= $index % 2 === 1 ? '<mark>'.e($part).'</mark>' : e($part);
        }

        return $html;
    }
}
