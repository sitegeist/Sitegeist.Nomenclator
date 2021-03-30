<?php
namespace Sitegeist\Nomenclator\Application\Controller;

/*                                                                                   *
 * This script belongs to the Neos Flow package "Sitegeist.Nomenclator". *
 *                                                                                   */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\RestController;
use Sitegeist\Nomenclator\Domain\GlossaryEntryFactory;

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
    protected $viewFormatToObjectNameMap = ['json' => 'Neos\Flow\Mvc\View\JsonView'];

    public function findEntryAction(string $identifier)
    {
        $glossaryEntry = $this->glossaryEntryFactory->fromNodeIdentifier($identifier);
        $this->view->assign('value', $glossaryEntry);
    }
}
