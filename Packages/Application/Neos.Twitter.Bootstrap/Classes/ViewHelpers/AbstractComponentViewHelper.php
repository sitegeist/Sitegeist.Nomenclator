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

use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Neos\FluidAdaptor\View\StandaloneView;

/**
 * Abstract Component View Helper
 */
class AbstractComponentViewHelper extends AbstractViewHelper
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Get a StandaloneView used for rendering the component
     *
     * @return StandaloneView
     */
    protected function getView()
    {
        $view = new StandaloneView($this->controllerContext->getRequest());
        if (is_file($this->settings['viewHelpers']['templates'][get_class($this)])) {
            $view->setPartialRootPath($this->settings['viewHelpers']['partialRootPath']);
            $view->setTemplatePathAndFilename($this->settings['viewHelpers']['templates'][get_class($this)]);
        }
        return $view;
    }
}
