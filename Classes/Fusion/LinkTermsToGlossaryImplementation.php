<?php
namespace Sitegeist\Nomenclator\Fusion;

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Sitegeist\Nomenclator\Domain\Glossary;
use Neos\Fusion\FusionObjects\AbstractFusionObject;
use Neos\Eel\FlowQuery\FlowQuery;

class LinkTermsToGlossaryImplementation extends AbstractFusionObject
{

    /**
     * @Flow\Inject
     * @var Glossary
     */
    protected $glossary;

    /**
     * Glossary node of the site
     *
     * @return TraversableNodeInterface
     */
    public function getGlossaryNode()
    {
        /** @var $contentContext ContentContext */
        $contentContext = $this->getNode()->getContext();

        $site = $contentContext->getCurrentSiteNode();

        $glossaryNodes = (new FlowQuery([$site]))->find('[instanceof Sitegeist.Nomenclator:Content.Glossary]')->get();

        return reset($glossaryNodes);
    }

    /**
     * The string to be processed
     *
     * @return string
     */
    public function getValue()
    {
        return $this->fusionValue('value');
    }

    /**
     * the node to be processed
     *
     * @return TraversableNodeInterface
     */
    public function getNode()
    {
        return $this->fusionValue('node');
    }


    public function evaluate()
    {


        $glossaryNode = $this->getGlossaryNode();
        $isInBackend = $this->getNode()->getContext()->isInBackend();
        $content = $this->getValue();

        if(!$glossaryNode->getProperty('linkingActivated') || $isInBackend || !$glossaryNode) {

            return $content;
        }

        $glossaryIndex = $this->glossary->readGlossaryIndexFromCache($glossaryNode);

        $originals = $replaces =[];

        $wrappingPattern = '<a class="nomenclator_enrty" href="glossaryURL/%s" >%s</a>';

        foreach ($glossaryIndex as $key => $value) {
            $originals[] = $key;
            $replaces[] = sprintf($wrappingPattern,$value,$key);
        }

        $lastBracket = '';

        $processedContent = "" ;

        while(strlen($content)){

            $nextLaBracket = (strpos($content,'<') === false) ? strlen($content) - 1 : strpos($content,'<') ;
            $nextRaBracket = (strpos($content,'>') === false) ? strlen($content) - 1 : strpos($content,'>') ;

            $currentPosition = min ($nextLaBracket , $nextRaBracket) ;

            $currentBracket = $content[$currentPosition];

            $buffer = substr($content,0,++$currentPosition);

            $content = substr($content, $currentPosition);


            if($lastBracket == '<' && $currentBracket == '>') {

                $processedContent = $processedContent . $buffer;

            } else {

                $processedContent = $processedContent . str_replace($originals, $replaces, $buffer);

            }

            $lastBracket=$currentBracket;

        }

        return $processedContent;

    }

}
