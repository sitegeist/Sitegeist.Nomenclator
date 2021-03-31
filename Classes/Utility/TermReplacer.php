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
        // Namespace needs to be registered due to limitations in Masterminds\HTML5
        // see: https://github.com/Masterminds/html5-php/issues/57
        $xpath->registerNamespace('html', 'http://www.w3.org/1999/xhtml');

        // The XPath Query selects all text nodes that are not
        // descendants of links
        $nodes = $xpath->query('//text()[not(ancestor::html:a) and not(ancestor::html:script)]');
        foreach ($nodes as $node) {
            foreach ($terms as $term) {
                // Regex left out for simplicity's sake
                $matches = preg_split('/(\b' . preg_quote($term) . '\b)/i', $node->nodeValue, -1, PREG_SPLIT_DELIM_CAPTURE);

                if (count($matches) > 1) {
                    $fragment = $doc->createDocumentFragment();

                    foreach ($matches as $match) {
                        if (\mb_strtolower($match) === \mb_strtolower($term)) {
                            $link = $onReplaceTerm($doc, $match, $term);
                            $fragment->appendChild($link);
                        } else {
                            $text = $doc->createTextNode($match);
                            $fragment->appendChild($text);
                        }
                    }

                    $node->parentNode->replaceChild($fragment, $node);
                }
            }
        }

        return $html5->saveHTML($doc->childNodes[1]->childNodes[0]->childNodes);
    }
}