<?php
namespace Sitegeist\Nomenclator\Domain;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Exception\NodeException;
use Neos\Flow\Annotations as Flow;

class GlossaryEntry implements \JsonSerializable
{
    /**
     * @var string
     */
    private $shortDescription;


    public function __construct( string $shortDescription )    {

        $this->shortDescription = $shortDescription;
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
