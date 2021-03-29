<?php
namespace Neos\Twitter\Bootstrap\ViewHelpers\Navigation;

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
use Neos\Twitter\Bootstrap\ViewHelpers\AbstractComponentViewHelper;

/**
 *
 * @Flow\Scope("prototype")
 */
class MenuViewHelper extends AbstractComponentViewHelper
{
    /**
     * Initialize the arguments.
     *
     * @return void
     * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('items', 'array', 'Items to render', true);
        $this->registerArgument('classNames', 'array', 'CSS classes to add to the menu', false, ['nav']);
    }

    /**
     * Render the menu
     *
     * @return string
     */
    public function render(): string
    {
        $view = $this->getView();

        $view->assignMultiple([
            'items' => $this->arguments['items'],
            'settings' => $this->settings,
            'menuClasses' => implode(' ', $this->arguments['classNames'])
        ]);

        return $view->render();
    }
}
