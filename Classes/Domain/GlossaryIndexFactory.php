<?php
namespace Sitegeist\Nomenclator\Domain;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContextFactory;

final class GlossaryIndexFactory
{
    /**
     * @Flow\InjectConfiguration(path="glossary")
     * @var array
     */
    protected $settings;

    public function fromNode(TraversableNodeInterface $glossaryNode): GlossaryIndex
    {
        $nodeType = $glossaryNode->getNodeType();

        if (!$nodeType->isOfType('Sitegeist.Nomenclator:Content.Glossary')) {
            return GlossaryIndexInvalid::becauseNodeIsNotOfTypeGlossary();
        }

        $glossaryIndex = [];

        $termSeparator = $this->settings['separator'];

        if ($glossaryNode) {
            $terms = $nodeIdentifiers = $titles = $duplicates = [];

            $entryNodes = $glossaryNode->findChildNodes(new NodeTypeConstraints(false, ['Sitegeist.Nomenclator:Content.Glossary.Entry']));

            foreach ($entryNodes as $entryNode) {
                if ($entryNode->getProperty('title')) {
                    $variants =  explode($termSeparator, $entryNode->getProperty('variants'));

                    $variants = array_filter($variants, function ($value) {
                        return $value !== ' ' && $value !== '';
                    });

                    $title = $entryNode->getProperty('title');

                    $title = trim(str_replace(['&nbsp;', '<br>'], '', $title));

                    $nodeIdentifier = $entryNode->getNodeAggregateIdentifier();

                    $terms[] = $title;

                    $nodeIdentifiers[] = $nodeIdentifier;

                    $titles[(string)$nodeIdentifier] = $title;

                    foreach ($variants as $variant) {
                        $variant = trim(str_replace(['&nbsp;', '<br>'], '', $variant));

                        if ($variant !== '') {
                            $terms[] = $variant;
                            $nodeIdentifiers[] = $nodeIdentifier;
                        }
                    }
                }
            }

            if ($duplicates = $this->glossaryDuplicates($terms, $nodeIdentifiers, $titles)) {
                throw GlossaryIndexInvalid::becauseThereAreSomeDuplicates($duplicates);
            }

            $glossaryIndex['terms'] = array_combine($terms, $nodeIdentifiers);
            $glossaryIndex['titles'] = $titles;
        }
        return new self($glossaryIndex);
    }

    private function glossaryDuplicates(array $terms, array $nodeIdentifiers, array $titles): array
    {

        $duplicates = [];

        $counts = array_count_values($terms);

        foreach ($terms as $index => $value) {
            if ($counts[$value] > 1) {
                $duplicates[] = $titles[(string)$nodeIdentifiers[$index]];
            }
        }

        if ($duplicates) {
            return array_values(array_unique($duplicates));
        }

        return $duplicates;
    }
}