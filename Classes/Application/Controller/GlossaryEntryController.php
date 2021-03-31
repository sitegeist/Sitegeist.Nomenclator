<?php
namespace Sitegeist\Nomenclator\Application\Controller;

/*                                                                                   *
 * This script belongs to the Neos Flow package "Sitegeist.Nomenclator". *
 *                                                                                   */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\RestController;
use Neos\Flow\Mvc\View\JsonView;
use Sitegeist\Nomenclator\Domain\GlossaryEntry;

class GlossaryEntryController extends RestController
{
    /**
     * @Flow\Inject
     * @var ContentContextFactory
     */
    protected $contentContextFactory;

    /**
     * @var array
     */
    protected $supportedMediaTypes = ['application/json'];

    /**
     * @var array
     */
    protected $viewFormatToObjectNameMap = [
        'json' => JsonView::class
    ];

    /**
     * Assigns a node to the NodeView.
     *
     * @param string $identifier The node to render
     * @return void
     */
    public function findEntryAction(string $identifier)
    {
        $contentContext = $this->contentContextFactory->create([]);
        $glossaryEntryNode = $contentContext->getNodeByIdentifier($identifier);

        $glossaryEntry = GlossaryEntry::fromNode($glossaryEntryNode);
        $this->view->assign('value', $glossaryEntry);
    }
}
