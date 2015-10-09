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

final class Edit extends AbstractContact
{
	/**
	 * Shows edit form
	 * 
	 * @param string $id Contact's id
	 * @return string
	 */
	public function indexAction($id)
	{
		$contact = $this->getContactManager()->fetchById($id);

		if ($contact !== false) {
			$this->loadSharedPlugins();
			return $this->view->render($this->getTemplatePath(), $this->getSharedVars(array(
				'title' => 'Edit the contact',
				'contact' => $contact
			)));

		} else {
			return false;
		}
	}

	/**
	 * Updates a contact
	 * 
	 * @return string
	 */
	public function updateAction()
	{
		$formValidator = $this->getValidator($this->request->getPost());

		if ($formValidator->isValid()) {
			if ($this->getContactManager()->update($this->request->getPost())) {
				$this->flashBag->set('success', 'The contact has been updated successfully');
				return '1';
			}

		} else {
			return $formValidator->getErrors();
		}
	}
}
