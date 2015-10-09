<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Contact;

use Cms\AbstractCmsModule;
use Contact\Service\ContactManager;

final class Module extends AbstractCmsModule
{
	/**
	 * {@inheritDoc}
	 */
	public function getServiceProviders()
	{
		$contactMapper = $this->getMapper('/Contact/Storage/MySQL/ContactMapper');
		$defaultMapper = $this->getMapper('/Contact/Storage/MySQL/DefaultMapper');

		$historyManager = $this->getHistoryManager();

		return array(
			'contactManager' => new ContactManager($contactMapper, $defaultMapper, $historyManager)
		);
	}
}
