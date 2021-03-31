<?php
namespace Sitegeist\Nomenclator\Domain;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Exception\NodeException;
use Neos\Flow\Annotations as Flow;

final class GlossaryEntry implements \JsonSerializable
{
    /**
     * @var string
     */
    private $shortDescription;


    private function __construct(string $shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    public static function fromNode(TraversableNodeInterface $node): self
    {
        if (!$node->getNodeType()->isOfType('Sitegeist.Nomenclator:Content.Glossary.Entry')) {
            throw GlossaryEntryInvalid::becauseNodeIsNotOfTypeGlossaryEntry();
        }

        $shortDescription = $node->getProperty('shortDescription');
        return new self($shortDescription);
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function jsonSerialize(): array
    {
        return [
            'shortDescription' => $this->shortDescription
            ];
    }
}
