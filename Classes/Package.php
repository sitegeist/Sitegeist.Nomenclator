<?php
namespace Sitegeist\Nomenclator;

use Neos\ContentRepository\Domain\Service\PublishingService;
use Neos\ContentRepository\Domain\Model\Workspace;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package as BasePackage;
use Sitegeist\Nomenclator\Domain\Glossary;

/**
 * The Sitegeist.Nomenclator
 */
class Package extends BasePackage
{
    /**
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect(
            Workspace::class,
            'beforeNodePublishing',
            Glossary::class,
            'beforeGlossaryPublished'
        );
    }
}
