<?php

use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;

class GlossaryIndex
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
        throw new \Exception('Not implemented yet!');
    }

    public function getTitleForTerm(): string
    {
        throw new \Exception('Not implemented yet!');
    }

    public function getNodeIdentifierForTerm(): NodeAggregateIdentifier
    {
        throw new \Exception('Not implemented yet!');
    }
}
