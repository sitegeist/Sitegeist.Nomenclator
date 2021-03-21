<?php
namespace Sitegeist\Nomenclator\Domain;

use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Exception\NodeException;
use Neos\Flow\Annotations as Flow;

class Glossary
{
    /**
     * @Flow\Inject
     * @var \Neos\Cache\Frontend\VariableFrontend
     */
    protected $glossaryIndexCache;

    /**
     * @Flow\InjectConfiguration(path="glossary")
     * @var array
     */
    protected $settings;

    /**
     * @var string
     */
    private $cacheIdentifier = "testGlossaryCache";

    public function beforeGlossaryPublished(TraversableNodeInterface $node, Workspace $workspace) :void
    {
        if ($node->getNodeType()->isOfType('Sitegeist.Nomenclator:Glossary.Entry')) {
            $glossaryNode = $node->findParentNode();
            $this->saveGlossaryInCache($glossaryNode);
            }
    }

    public function saveGlossaryInCache(TraversableNodeInterface $glossaryNode) {
        $glossaryIndex = $this->extractGlossaryIndexFromNode($glossaryNode);

        if ($glossaryIndex) {
            $this->glossaryIndexCache->flush();
            $this->glossaryIndexCache->set($this->cacheIdentifier, serialize($glossaryIndex), [], 86400);
        }
        file_put_contents('./debug.txt', json_encode($glossaryIndex).PHP_EOL , FILE_APPEND | LOCK_EX);
    }

    /**
     * Returns Glossary Index Cache
     * @param TraversableNodeInterface $glossaryNode
     * @return array
     * @throws \Neos\Cache\Exception
     */
    public function readGlossaryIndexFromCache(TraversableNodeInterface $glossaryNode): array
    {
        if ($this->glossaryIndexCache->has($this->cacheIdentifier)) {
            return unserialize($this->glossaryIndexCache->get($this->cacheIdentifier));
        } else {
            return $this->glossaryIndexCache->set($this->cacheIdentifier, $this->extractGlossaryIndexFromNode($glossaryNode));
        }
    }

    protected function extractGlossaryIndexFromNode(TraversableNodeInterface $glossaryNode) : array
    {
        $nodeType = $glossaryNode->getNodeType();

        if (!$nodeType->isOfType('Sitegeist.Nomenclator:Glossary')) {
            return [];
        }

        $glossaryIndex=[];

        $termSeparator = $this->settings['separator'];

        if ($glossaryNode) {
            $terms = $nodeIdentifiers = $termsIndex = $duplicates =[];

            $entryNodes = $glossaryNode->findChildNodes(new NodeTypeConstraints(false, ['Sitegeist.Nomenclator:Glossary.Entry']));

            foreach ( $entryNodes as $entryNode) {
                if ($entryNode->getProperty('title')) {

                    $variants =  explode($termSeparator , $entryNode->getProperty('variants'));
                    $variants = array_filter($variants, function($value) {
                        return $value !== ' ' && $value !== '';
                    });

                    $title = $entryNode->getProperty('title');
                    $title = trim (str_replace(['&nbsp;', '<br>'], '', $title));

                    $nodeIdentifier = $entryNode->getNodeAggregateIdentifier();

                    $terms[] = $title;

                    $nodeIdentifiers[] = $nodeIdentifier;

                    $termsIndex[(string)$nodeIdentifier] = $title;


                    foreach ($variants as $variant)  {
                        $terms[]=  trim (str_replace(['&nbsp;', '<br>'], '', $variant));
                        $nodeIdentifiers[] = $nodeIdentifier;
                    }
                }
            }

            if ($duplicates = $this->glossaryDuplicates($terms, $nodeIdentifiers, $termsIndex)) {
                throw GlossaryEntryInvalid::becauseThereAreSomeDuplicates($duplicates);
            }

            $glossaryIndex = array_combine ($terms, $nodeIdentifiers );
        }

        return $glossaryIndex;
    }

    private function glossaryDuplicates(array $terms,array $nodeIdentifiers, array $termsIndex) : array
    {

        $duplicates=[];

        $counts = array_count_values($terms);

        foreach ($terms as $index => $value) {
            if ($counts[$value] > 1) {
                $duplicates[] = $termsIndex[(string)$nodeIdentifiers[$index]];
            }
        }

        if ($duplicates) {
            return array_values(array_unique($duplicates));
        }

        return $duplicates;
    }
}
