<?php
namespace Sitegeist\Nomenclator\Domain;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContextFactory;

class GlossaryEntryFactory
{
    /**
     * @Flow\Inject
     * @var ContentContextFactory
     */
    protected $contentContextFactory;

    /**
     * @param string $glossaryEntryNodeIdentifier
     * @return GlossaryEntry
     */
    public function fromNodeIdentifier(string $glossaryEntryNodeIdentifier)
    {
        $contentContext = $this->contentContextFactory->create([]);
        $glossaryEntryNode = $contentContext->getNodeByIdentifier($glossaryEntryNodeIdentifier);
        $shortDescription = $glossaryEntryNode->getProperty('shortDescription');
        return new GlossaryEntry($shortDescription);
    }
}
