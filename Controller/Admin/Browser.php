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

use Cms\Controller\Admin\AbstractController;

final class Browser extends AbstractController
{
	/**
	 * Shows a table
	 * 
	 * @param integer $page Current page
	 * @return string
	 */
	public function indexAction($page = 1)
	{
		$this->loadPlugins();

		$contactManager = $this->getContactManager();
		$contacts = $contactManager->fetchAllByPage($page, $this->getSharedPerPageCount());

		$paginator = $contactManager->getPaginator();
		$paginator->setUrl('#'); //@TODO this

		return $this->view->render('browser', array(
			'title' => 'Contacts',
			'contacts' => $contacts,
			'paginator'	=> $paginator
		));
	}

	/**
	 * Returns contact manager
	 * 
	 * @return \Contact\Service\ContactManager
	 */
	private function getContactManager()
	{
		return $this->getModuleService('contactManager');
	}

	/**
	 * Loads required plugins for view
	 * 
	 * @return void
	 */
	private function loadPlugins()
	{
		$this->view->getPluginBag()
				   ->appendScript($this->getWithAssetPath('/admin/browser.js'));

		$this->view->getBreadcrumbBag()->add(array(
			array(
				'name' => 'Contacts',
				'link' => '#'
			)
		));
	}

	/**
	 * Saves table configuration
	 * 
	 * @return string The response
	 */
	public function saveAction()
	{
		if ($this->request->hasPost('published', 'order') && $this->request->isAjax()) {
			
			$published	= $this->request->getPost('published');
			$order		= $this->request->getPost('order');
			//@TODO
		}
	}

	/**
	 * Deletes a contact by its associated id
	 * 
	 * @return string
	 */
	public function deleteAction()
	{
		if ($this->request->hasPost('id') && $this->request->isAjax()) {

			$id = $this->request->getPost('id');

			$this->getContactManager()->deleteById($id);
			$this->flashMessenger->set('success', 'Selected contact has been removed successfully');

			return '1';
		}
	}

	/**
	 * Delete selected contacts by their associated ids
	 * 
	 * @return string
	 */
	public function deleteSelectedAction()
	{
		if ($this->request->hasPost('toDelete')) {

			//TODO
			$flashKey = 'success';
			$flashMessage = 'Selected contacts have been removed successfully';

		} else {

			$flashKey = 'warning';
			$flashMessage = 'You should select at least on contact to remove';
		}

		$this->flashMessenger->set($flashKey, $flashMessage);
		return '1';
	}
}