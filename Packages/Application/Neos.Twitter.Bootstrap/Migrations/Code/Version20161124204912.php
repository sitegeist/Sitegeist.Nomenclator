<?php
namespace Neos\Flow\Core\Migrations;

/*
 * This file is part of the Neos.Flow package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

/**
 * Adjusts code to package renaming from "TYPO3.Twitter.Bootstrap" to "Neos.Twitter.Bootstrap"
 */
class Version20161124204912 extends AbstractMigration
{

	public function getIdentifier()
	{
		return 'Neos.Twitter.Bootstrap-20161124204912';
	}

	/**
	 * @return void
	 */
	public function up()
	{
		$this->searchAndReplace('TYPO3\Twitter\Bootstrap', 'Neos\Twitter\Bootstrap');
		$this->searchAndReplace('TYPO3.Twitter.Bootstrap', 'Neos.Twitter.Bootstrap');
	}
}
