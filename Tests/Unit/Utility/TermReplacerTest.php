<?php
namespace Sitegeist\Nomenclator\Tests\Unit\Utility;

use Neos\Flow\Tests\UnitTestCase;
use Sitegeist\Nomenclator\Utility\TermReplacer;

final class TermReplacerTest extends UnitTestCase
{
    /**
     * @test
     * @return void
     */
    public function wrapsElementsAroundTerms(): void
    {
        $content = 'This is a test string.';
        $terms = ['test'];
        $expectedResult = 'This is a <span>test</span> string.';

        $result = TermReplacer::replaceTerms($content, $terms, function (\DOMDocument $doc, $match, $term) {
            return $doc->createElement('span', $match);
        });

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @return void
     */
    public function matchesElementsOnlyWhenTheyAreIsolated(): void
    {
        $content = 'Dies sind Blumen. Dies ist eine Blumenvase.';
        $terms = ['Blumen'];
        $expectedResult = 'Dies sind <span>Blumen</span>. Dies ist eine Blumenvase.';

        $result = TermReplacer::replaceTerms($content, $terms, function (\DOMDocument $doc, $match, $term) {
            return $doc->createElement('span', $match);
        });

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @return void
     */
    public function escapesTermsForUseInRegularExpression(): void
    {
        $content = 'This is a test string.';
        $terms = ['(test)'];
        $expectedResult = 'This is a test string.';

        $result = TermReplacer::replaceTerms($content, $terms, function (\DOMDocument $doc, $match, $term) {
            return $doc->createElement('span', $match);
        });

        $this->assertEquals($expectedResult, $result);
    }
}