<?php
namespace Sitegeist\Nomenclator\Domain;

use GlossaryIndex;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Utility\SubgraphUtility;

final class Glossary
{
    /**
     * @Flow\Inject
     * @var \Neos\Cache\Frontend\VariableFrontend
     */
    protected $glossaryIndexCache;

    /**
     * @Flow\Inject
     * @var GlossaryIndexFactory
     */
    protected $glossaryIndexFactory;

    public function beforeGlossaryPublished(TraversableNodeInterface $node, Workspace $workspace) :void
    {
        if ($node->getNodeType()->isOfType('Sitegeist.Nomenclator:Content.Glossary.Entry')) {
            $glossaryNode = $node->findParentNode();
            $this->saveGlossaryInCache($glossaryNode);
        }
    }

    public function saveGlossaryInCache(TraversableNodeInterface $glossaryNode) :array
    {
        $glossaryIndex = $this->glossaryIndexFactory->fromNode($glossaryNode);

        if ($glossaryIndex) {
            $this->glossaryIndexCache->flush();
            $this->glossaryIndexCache->set($this->getCacheIdentifierFromNode($glossaryNode), serialize($glossaryIndex), [], 86400);
        }
        return $glossaryIndex;
    }

    /**
     * Returns Glossary Index Cache
     * @param TraversableNodeInterface $glossaryNode
     * @return array
     * @throws \Neos\Cache\Exception
     */
    public function readGlossaryIndexFromCache(TraversableNodeInterface $glossaryNode): GlossaryIndex
    {

        if ($this->glossaryIndexCache->has($this->cacheIdentifier)) {
            return unserialize($this->glossaryIndexCache->get($this->getCacheIdentifierFromNode($glossaryNode)));
        } else {
            return $this->saveGlossaryInCache($glossaryNode);
        }
    }

    protected function getCacheIdentifierFromNode(TraversableNodeInterface $glossaryNode): string
    {
        $identifier = (string) $glossaryNode->getNodeAggregateIdentifier();
        $identifier .= '_' . SubgraphUtility::hashIdentityComponents($glossaryNode->getContext()->getProperties());

        return $identifier;
    }
}
