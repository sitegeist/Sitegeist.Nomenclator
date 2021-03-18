<?php
namespace Sitegeist\Nomenclator\Command;

/*                                                                                         *
 * This script is just a test and must be removed at the end of the project. *
 *                                                                                         */

use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Exception\NodeException;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Neos\Domain\Repository\SiteRepository;
use Neos\Neos\Domain\Service\ContentContextFactory;

/**
 * Product URI command controller
 */
class TryCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var \Neos\Cache\Frontend\VariableFrontend
     */
    protected $glossaryIndexCache;

    /*public function test2Command() :void
    {
        $index['test1'] = "cache content test1";
        $index['test2'] = "cache content test2";
        $cacheIdentifier = "testGlossaryCache";
        $this->glossaryIndexCache->set($cacheIdentifier, serialize($index), [], 86400);

        $index= unserialize($this->glossaryIndexCache->get($cacheIdentifier));
        if($index) {
            $txt = $index['test2'];
            file_put_contents('debug.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
        }

        file_put_contents('./Web/debug.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    }*/
    /**
     * @Flow\Inject
     * @var ContentContextFactory
     */
    protected $contentContextFactory;

    /**
     * @var SiteRepository
     * @Flow\Inject
     */
    protected $siteRepository;

    /**
     * @var string
     */
    private $termSeparator = ',';

    public function testCommand(): void
    {
        $site = $this->siteRepository->findOneByNodeName('customer-site');
        $dimensions= ['de'];
        $contentContext = $this->contentContextFactory->create([
            'workspaceName' => 'live',
            'dimensions' => $dimensions,
            'currentSite' => $site,
            'currentDomain' => $site->getFirstActiveDomain(),
        ]);
        $entryNodes = [];
        $duplicatedTxt = [];
        $glossaryNode = $contentContext->getNodeByIdentifier('592237fc-5532-4696-9e87-804daa7e40ee');
        $glossaryNode = $glossaryNode->findParentNode();
        if ($glossaryNode) {
            $terms = $identifiers = $duplicates =[];
        } else{
            $txt = "didn't find the node";
        }

        file_put_contents('./Web/debug.txt', "----------------------------------------".PHP_EOL , FILE_APPEND | LOCK_EX);
        file_put_contents('./Web/debug.txt', "terms:".PHP_EOL , FILE_APPEND | LOCK_EX);
        file_put_contents('./Web/debug.txt', "----------------------------------------".PHP_EOL , FILE_APPEND | LOCK_EX);
        file_put_contents('./Web/debug.txt', "----------------------------------------".PHP_EOL , FILE_APPEND | LOCK_EX);
    }

    public function test2Command(): void
    {
        file_put_contents('./Web/debug.txt', "----------------------------------------".PHP_EOL , FILE_APPEND | LOCK_EX);
        $string = '1, 2, ,3,';
        $string = array_filter(explode(',',$string), function($value) { return $value !== ' ' && $value !== ''; });
        file_put_contents('./Web/debug.txt', json_encode($string).PHP_EOL , FILE_APPEND | LOCK_EX);
        file_put_contents('./Web/debug.txt', "----------------------------------------".PHP_EOL , FILE_APPEND | LOCK_EX);
    }
}
