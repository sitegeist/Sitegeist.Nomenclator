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

    public function beforeGlossaryPublished(TraversableNodeInterface $node, Workspace $workspace) :void
    {
        if ($node->getNodeType()->isOfType('Sitegeist.Nomenclator:Glossary.Entry')) {
            $glossaryIndex = self::extractGlossaryIndexFromNode($node);

            if ($glossaryIndex) {
                $cacheIdentifier = "testGlossaryCache";
                $this->glossaryIndexCache->set($cacheIdentifier, serialize($glossaryIndex), [], 86400);
            }
        }
    }



    public static function extractGlossaryIndexFromNode(TraversableNodeInterface $node) : array
    {
        $glossaryIndex=[];
        $termSeparator = ',';

        if ($node->getNodeType()->isOfType('Sitegeist.Nomenclator:Glossary.Entry')) {
            try {
                $glossaryNode = $node->findParentNode();
            } catch (NodeException $e) {
                return [];
            }
        } elseif ($node->getNodeType()->isOfType('Sitegeist.Nomenclator:Glossary')) {
            $glossaryNode = $node;
        }

        if ($glossaryNode) {
            $terms = $identifiers = $duplicates =[];

            $entryNodes = $glossaryNode->findChildNodes(new NodeTypeConstraints(false, ['Sitegeist.Nomenclator:Glossary.Entry']));

            foreach ( $entryNodes as $entryNode) {
                if ($entryNode->getProperty('title')) {

                    $variants =  explode($termSeparator , $entryNode->getProperty('variants'));
                    $variants = array_filter($variants, function($value) {
                        return $value !== ' ' && $value !== '';
                    });

                    $title= $entryNode->getProperty('title');

                    $terms[]= trim (str_replace(['&nbsp;', '<br>'], '', $title));

                    $identifiers[] = $entryNode->getNodeAggregateIdentifier();

                    foreach ($variants as $variant)  {
                        $terms[]=  trim (str_replace(['&nbsp;', '<br>'], '', $variant));
                        $identifiers[] = $entryNode->getNodeAggregateIdentifier();
                    }
                }
            }

            foreach (array_count_values($terms) as $val => $count) {
                if ($count > 1) $duplicates[] = $val;
            }

            if ($duplicates) {
                throw GlossaryEntryInvalid::becauseThereAreSomeDuplicates($duplicates);
            }

            $glossaryIndex = array_combine ($terms, $identifiers );
        }

        return $glossaryIndex;
    }
}
