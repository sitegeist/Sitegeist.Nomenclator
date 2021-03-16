<?php
namespace Sitegeist\Nomenclator\Domain;

use Neos\ContentRepository\Domain\Model\Workspace;
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

    public function whenGlossaryPublished(TraversableNodeInterface $node, Workspace $workspace) :void
    {
        $index['test1'] = "this is test1";
        $index['test2'] = "this is test2";
        $cacheIdentifier = "testGlossaryCache";
        $this->glossaryIndexCache->set($cacheIdentifier, $index, [], 86400);

        $index= $this->glossaryIndexCache->get($cacheIdentifier);
        if($index) {
            $txt = $index['test2'];
            file_put_contents('debug.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
        }
    }
}
