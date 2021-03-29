<?php
namespace Neos\Twitter\Bootstrap\ViewHelpers;

/*
 * This file is part of the Neos.Twitter.Bootstrap package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;

/**
 * Bootstrap Include View Helper
 */
class IncludeViewHelper extends AbstractViewHelper
{
    /**
     * @var ResourceManager
     * @Flow\Inject
     */
    protected $resourceManager;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize the arguments.
     *
     * @return void
     * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('version', 'string', 'The version to use, for example "2.2", "3.0" or also "2" or "3" meaning "2.x" and "3.x" respectively', true);
        $this->registerArgument('minified', 'string', 'If the minified version of Twitter Bootstrap should be used', false, true);
        $this->registerArgument('includeJQuery', 'string', 'If enabled, also includes jQuery', false, false);
        $this->registerArgument('jQueryVersion', 'string', 'The jQuery version to include', false, '1.10.1');
    }

    /**
     * Get the header include code for including Twitter Bootstrap on a page. If needed
     * the jQuery library can be included, too.
     *
     * Example usage:
     * {namespace bootstrap=Neos\Twitter\Bootstrap\ViewHelpers}
     * <bootstrap:include />
     *
     * @return string
     */
    public function render(): string
    {
        $version = $this->arguments['version'];
        $minified = $this->arguments['minified'];
        $jQueryVersion = $this->arguments['jQueryVersion'];

        $content = sprintf(
            '<link rel="stylesheet" href="%s" />' . PHP_EOL,
            $this->resourceManager->getPublicPackageResourceUri('Neos.Twitter.Bootstrap', $version . '/css/bootstrap' . ($minified === true ? '.min' : '') . '.css')
        );

        if ($this->arguments['includeJQuery'] === true) {
            $content .= sprintf(
                '<script src="%s"></script>' . PHP_EOL,
                $this->resourceManager->getPublicPackageResourceUri('Neos.Twitter.Bootstrap', 'Libraries/jQuery/jquery-' . $jQueryVersion . ($minified === true ? '.min' : '') . '.js')
            );
        }

        $content .= sprintf(
            '<script src="%s"></script>' . PHP_EOL,
            $this->resourceManager->getPublicPackageResourceUri('Neos.Twitter.Bootstrap', $version . '/js/bootstrap' . ($minified === true ? '.min' : '') . '.js')
        );

        return $content;
    }
}
