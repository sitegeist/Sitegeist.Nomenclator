<?php
namespace Sitegeist\Nomenclator\Domain;

use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\Flow\Annotations as Flow;

/**
 * Class GlossaryIndex
 * @package Sitegeist\Nomenclator\Domain
 * @Flow\Proxy(false)
 */
final class GlossaryIndex
{
    /**
     * @var array
     */
    public $entries = [];

    /**
     * GlossaryIndex constructor.
     * @param array $entries
     */
    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    public function getTerms(): array
    {
        return array_keys($this->entries['terms']);
    }

    public function getTitleForTerm(string $term): string
    {
        $nodeIdentifier = $this->getNodeIdentifierForTerm($term);
        return $this->entries['titles'][(string)$nodeIdentifier];

    }

    public function getNodeIdentifierForTerm(string $term): NodeAggregateIdentifier
    {
        return $this->entries['terms'][$term];
    }
}
