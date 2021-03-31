<?php
namespace Sitegeist\Nomenclator\Fusion;

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Service\LinkingService;
use Sitegeist\Nomenclator\Domain\Glossary;
use Neos\Fusion\FusionObjects\AbstractFusionObject;
use Neos\Eel\FlowQuery\FlowQuery;
use Sitegeist\Nomenclator\Utility\TermReplacer;

class LinkTermsToGlossaryImplementation extends AbstractFusionObject
{

    /**
     * @Flow\Inject
     * @var Glossary
     */
    protected $glossary;

    /**
     * @Flow\Inject
     * @var LinkingService
     */
    protected $linkingService;

    /**
     * @param TraversableNodeInterface $documentNode
     * @param bool $absolute
     * @return string
     */

    protected function getNodeUri(TraversableNodeInterface $documentNode)
    {
        $controllerContext = $this->runtime->getControllerContext();
        $resolvedUri = $this->linkingService->createNodeUri($controllerContext, $documentNode);
        return $resolvedUri;
    }

    /**
     * @return null|TraversableNodeInterface
     */
    public function getGlossaryPage(): ?TraversableNodeInterface
    {
        return $this->fusionValue('glossaryPage');
    }

    /**
     * @return null|string
     */
    public function getGlossaryPageUri(): ?string
    {
        return $this->fusionValue('glossaryPageUri');
    }

    /**
     * The string to be processed
     *
     * @return string
     */
    public function getValue()
    {
        return $this->fusionValue('value');
    }

    public function evaluate()
    {
        $content = $this->getValue();

        if (!($glossaryPage = $this->getGlossaryPage())) {
            return $content;
        }

        if (!($glossaryPageUri = $this->getGlossaryPageuri())) {
            return $content;
        }

        $glossaryNode = $glossaryPage->getChildNode('main');
        $glossaryIndex = $this->glossary->readGlossaryIndexFromCache($glossaryNode);

        return TermReplacer::replaceTerms($content, $glossaryIndex->getTerms(), function (\DOMDocument $doc, string $match, string $term) use ($glossaryIndex, $glossaryPageUri) {
            $link = $doc->createElement('a', $match);
            $link->setAttribute('class', 'nomenclator_entry');
            $link->setAttribute('href', $glossaryPageUri . '#' . $glossaryIndex->getTitleForTerm($term));
            $link->setAttribute('data-identifier', (string) $glossaryIndex->getNodeIdentifierForTerm($term));

            return $link;
        });
    }
}
