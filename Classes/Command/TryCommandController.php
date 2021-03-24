<?php
namespace Sitegeist\Nomenclator\Command;

/*                                                                                         *
 * This script is just a test and must be removed at the end of the project. *
 *                                                                                         */

use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
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
        $dimensions = ['de'];
        $contentContext = $this->contentContextFactory->create([
            'workspaceName' => 'live',
            'dimensions' => $dimensions,
            'currentSite' => $site,
            'currentDomain' => $site->getFirstActiveDomain(),
        ]);

        $glossaryNode = $contentContext->getNodeByIdentifier('592237fc-5532-4696-9e87-804daa7e40ee');
        $glossaryNode = $glossaryNode->findParentNode();
    }



    public function test3Command() :void
    {
        $pattern = '#\<div\>(.+?)\<\/div\>#s';
        $content = '<div>get me1</div><div>get me2</div><div>get me3</div>';
        $content_processed = preg_replace_callback(
            $pattern,
            function($matches) {
                return "<pre>".htmlentities($matches[1])."</pre>";
            },
            $content
        );
        file_put_contents('./Web/debug.txt', $content_processed. PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public function test4Command() :void
    {
        $pattern = '/<.+?>|.*?(.+?)(?:$|<.+?>)/';
        $content = '<div>get me2</div><div>get me3</div></div>';
        $content_processed = preg_replace_callback(
            $pattern,
            function($matches) {
                file_put_contents('./Web/debug.txt', json_encode($matches). PHP_EOL, FILE_APPEND | LOCK_EX);
                return "<p>".$matches[1]."</p>";
            },
            $content
        );
        //file_put_contents('./Web/debug.txt', $content_processed. PHP_EOL, FILE_APPEND | LOCK_EX);
    }



    public function test6Command() :void
    {
       /* $pattern = '/<.+?>|.*?(.+?)($|<.+?>)/';*/
        $pattern = '/<.+?>|.*?(.+?)(?:$|<.+?>)/';
        $content = '<dis><dis><dis><dis>this <dis> the second one<diz/>is just a </di>twerkjh werkjh wkejrh >';
        preg_match_all($pattern, $content, $matches);

        file_put_contents('./Web/debug.txt', json_encode($matches[1]). PHP_EOL, FILE_APPEND | LOCK_EX);
    }


    public function test7Command() :void
    {
        $i=0;
        $subject = "div>this is just some </div> test text </div> with some tags <img/> inside it</div>";
        $result = "";
        $from = ['some', 'text', 'tags', 'div'];
        $to = ['emos', 'xtet', 'sgat', 'vid'];

        $lastBracket = '';
        file_put_contents('./Web/debug.txt', "first subject". PHP_EOL, FILE_APPEND | LOCK_EX);
        file_put_contents('./Web/debug.txt', "-------------". PHP_EOL, FILE_APPEND | LOCK_EX);

        while(strlen($subject)){
            $i++;
            file_put_contents('./Web/debug.txt', "begining of round:"."$i". PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', '$subject'. PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', $subject. PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', 'strlen($subject)'. PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', strlen($subject). PHP_EOL, FILE_APPEND | LOCK_EX);


            $nextLaBracket = (strpos($subject,'<')=== false)? strlen($subject)-1 : strpos($subject,'<') ;
            $nextRaBracket = (strpos($subject,'>')=== false)? strlen($subject)-1 : strpos($subject,'>') ;
            file_put_contents('./Web/debug.txt', '$nextRab'. PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', $nextRaBracket. PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', '$nextLab'. PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', $nextLaBracket. PHP_EOL, FILE_APPEND | LOCK_EX);

            $currentPosition = min ($nextLaBracket , $nextRaBracket) ;
            file_put_contents('./Web/debug.txt', "currentPosition". PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', $currentPosition. PHP_EOL, FILE_APPEND | LOCK_EX);
            $currentBracket =$subject[$currentPosition];
            file_put_contents('./Web/debug.txt', '$currentBracket'. PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', $currentBracket. PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', '$lastBracket'. PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', $lastBracket. PHP_EOL, FILE_APPEND | LOCK_EX);
            $buffer = substr($subject,0,++$currentPosition);
            $subject = substr($subject, $currentPosition);
            file_put_contents('./Web/debug.txt', "subject". PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', $subject. PHP_EOL, FILE_APPEND | LOCK_EX);

            if($lastBracket == '<' && $currentBracket == '>') {
                file_put_contents('./Web/debug.txt', "no replace". PHP_EOL, FILE_APPEND | LOCK_EX);
                $result = $result . $buffer;
            } else {

                file_put_contents('./Web/debug.txt', "with replace". PHP_EOL, FILE_APPEND | LOCK_EX);
                $result = $result . str_replace($from, $to, $buffer);
            }

            $lastBracket=$currentBracket;

            file_put_contents('./Web/debug.txt', "result". PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', $result. PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('./Web/debug.txt', "end of round:"."$i"."\n\n\n". PHP_EOL, FILE_APPEND | LOCK_EX);

        }
    }




    /* public function evaluate()
    {
        file_put_contents('./debug.txt', "we are he".PHP_EOL , FILE_APPEND | LOCK_EX);

        $content = $this->getValue();

        $wrappingPattern = '<a class="nomenclator_enrty" href="glossaryURL/%s" >%s</a>';

        $processedContent =  $content ;

        file_put_contents('./debug.txt', $processedContent.PHP_EOL , FILE_APPEND | LOCK_EX);

        $glossaryNode = $this->getGlossaryNode();

        $glossaryIndex = $this->glossary->readGlossaryIndexFromCache($glossaryNode);



        foreach ($glossaryIndex as $key => $value) {
            $originals[] = $key;
            $replaces[] = sprintf($wrappingPattern,$value,$key);
        }

        file_put_contents('./debug.txt', json_encode($originals).PHP_EOL , FILE_APPEND | LOCK_EX);
        $isInBackend = $this->getNode()->getContext()->isInBackend();

        if($glossaryNode && !$isInBackend) {
            $pattern = '/<.+?>|.*?(.+?)(?:$|<.+?>)/';
            $processedContent = preg_replace_callback(
                $pattern,
                function($matches) use ($originals, $replaces) {
                    //file_put_contents('./debug.txt', json_encode($matches[1]).PHP_EOL , FILE_APPEND | LOCK_EX);
                    return str_replace($originals, $replaces, $matches[0]);
                },
                $processedContent
            );
            file_put_contents('./debug.txt', json_encode($originals).PHP_EOL , FILE_APPEND | LOCK_EX);
            return $processedContent;
        }

        //file_put_contents('./debug.txt', $content.PHP_EOL , FILE_APPEND | LOCK_EX);

        //file_put_contents('./debug.txt', json_encode($matches[1]).PHP_EOL , FILE_APPEND | LOCK_EX);

        //$glossaryIndex = $this->glossary->readGlossaryIndexFromCache();

        return $content;
    }*/

}
