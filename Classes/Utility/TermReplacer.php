<?php
namespace Sitegeist\Nomenclator\Utility;

use Masterminds\HTML5;

final class TermReplacer
{
    /**
     * @param string $markup
     * @param array $terms
     * @param callable $onReplaceTerm
     * @return string
     */
    public static function replaceTerms(string $markup, array $terms, callable $onReplaceTerm): string
    {
        $html5 = new HTML5();

        $doc = $html5->loadHTML(sprintf('<div>%s</div>', $markup));

        $xpath = new \DOMXPath($doc);

        $xpath->registerNamespace('html', 'http://www.w3.org/1999/xhtml');

        $nodes = $xpath->query('//text()[not(ancestor::html:a) and not(ancestor::html:script)]');

        foreach ($nodes as $node) {
            $fragment = $doc->createDocumentFragment();

            $matches = preg_split('/(\b(?:' . join('|', array_map('preg_quote', $terms)) . ')\b)/i', $node->nodeValue, -1, PREG_SPLIT_DELIM_CAPTURE);

            if (count($matches) > 1) {
                foreach ($matches as $match) {
                    if ($matchingTerms = preg_grep('/\b' . preg_quote($match) . '\b/i', $terms)) {
                        $matchingTerms = array_values($matchingTerms);

                        $link = $onReplaceTerm($doc, $match, $matchingTerms[0]);

                        $fragment->appendChild($link);
                    } else {
                        $text = $doc->createTextNode($match);
                        $fragment->appendChild($text);
                    }
                }
            }

            if (count($fragment->childNodes) > 0) {
                $node->parentNode->replaceChild($fragment, $node);
            }
        }
        return $html5->saveHTML($doc->childNodes[1]->childNodes[0]->childNodes);
    }
}
