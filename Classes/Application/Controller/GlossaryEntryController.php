<?php
namespace Sitegeist\Nomenclator\Application\Controller;

/*                                                                                   *
 * This script belongs to the Neos Flow package "Sitegeist.Nomenclator". *
 *                                                                                   */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\RestController;
use Sitegeist\Nomenclator\Domain\GlossaryEntryFactory;
use Neos\Flow\Mvc\View\JsonView;

class GlossaryEntryController extends RestController
{
    /**
     * @Flow\Inject
     * @var GlossaryEntryFactory
     */
    protected $glossaryEntryFactory;

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
        $glossaryEntry = $this->glossaryEntryFactory->fromNodeIdentifier($identifier);
        $this->view->assign('value', $glossaryEntry);
    }
}
