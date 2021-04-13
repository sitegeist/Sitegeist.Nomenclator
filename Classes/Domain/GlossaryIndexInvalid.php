<?php declare(strict_types=1);
namespace Sitegeist\Nomenclator\Domain;

/*
 * This file is part of the Sitegeist.Nomenclator package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;

/**
 * The exception to be thrown if there are some duplicates in entry terms
 * @Flow\Proxy(false)
 */
final class GlossaryIndexInvalid extends \DomainException
{
    public static function becauseThereAreSomeDuplicates(array $listOfDuplicates): self
    {
        return new self('The are some duplicated terms in the entries:  "'. implode(", ", $listOfDuplicates) . '"', 1616064392);
    }
    public static function becauseNodeIsNotOfTypeGlossary(TraversableNodeInterface $node): self
    {
        return new self('Instead of a Glossary node type a '. $node->getNodeType()->getName() .' was given ', 1617189820);
    }
}
