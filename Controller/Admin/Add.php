<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Contact\Controller\Admin;

final class Add extends AbstractContact
{
	/**
	 * Shows adding form
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$this->loadSharedPlugins();

		return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
			'title' => 'Add a contact',
			'contact' => $this->getContactManager()->fetchDummy()
		)));
	}

	/**
	 * Adds a contact
	 * 
	 * @return string
	 */
	public function addAction()
	{
		$formValidator = $this->getValidator($this->request->getPost('contact'));

		if ($formValidator->isValid()) {
			$contactManager = $this->getContactManager();

			if ($contactManager->add($this->request->getPost('contact'))) {
				$this->flashBag->set('success', 'A contact has been added successfully');
				return $contactManager->getLastId();
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
