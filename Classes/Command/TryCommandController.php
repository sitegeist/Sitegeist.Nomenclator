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
use Neos\Neos\Ui\Domain\Model\Feedback\Operations\ReloadContentOutOfBand;
use Neos\Neos\Ui\Domain\Model\FeedbackCollection;
use Neos\Neos\Ui\Domain\Model\RenderedNodeDomAddress;

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

    /**
     * @Flow\Inject
     * @var FeedbackCollection
     */
    protected $feedbackCollection;

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

        $glossaryNode = $contentContext->getNodeByIdentifier('592237fc-5532-4696-9e87-804daa7e40ee');
        $glossaryNode = $glossaryNode->findParentNode();




        /*foreach($terms as $key => $value ) {
            file_put_contents('./Web/debug.txt', $key.PHP_EOL , FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', json_encode($value).PHP_EOL , FILE_APPEND | LOCK_EX);

            file_put_contents('./Web/debug.txt', "-----------------".PHP_EOL , FILE_APPEND | LOCK_EX);
        }*/


        /*file_put_contents('./Web/debug.txt', "----------------------------------------".PHP_EOL , FILE_APPEND | LOCK_EX);
        file_put_contents('./Web/debug.txt', "terms:".PHP_EOL , FILE_APPEND | LOCK_EX);
        file_put_contents('./Web/debug.txt', "----------------------------------------".PHP_EOL , FILE_APPEND | LOCK_EX);
        file_put_contents('./Web/debug.txt', "----------------------------------------".PHP_EOL , FILE_APPEND | LOCK_EX);*/
    }

    public function test2Command(): void
    {

        $a = array('key1', 'key2', 'key3','key4', 'key5');
        $b = array('111', '222', '333', '333', '444');

        $c = array('111'=>'title1', '222'=>'title2', '333'=>'title3', '444'=> 'title4');

        $counts = array_count_values($a);

        //file_put_contents('./Web/debug.txt', json_encode($counts).PHP_EOL , FILE_APPEND | LOCK_EX);
        $duplicates=[];
        foreach ($a as $key => $value) {
            if ($counts[$value] > 1) {
                $duplicates[]= $c[$b[$key]];
                //file_put_contents('./Web/debug.txt', $key.PHP_EOL , FILE_APPEND | LOCK_EX);
            }
        }

        file_put_contents('./Web/debug.txt', json_encode(array_values(array_unique($duplicates))).PHP_EOL , FILE_APPEND | LOCK_EX);





    }
}
