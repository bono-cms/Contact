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
        $paginator->setUrl('/admin/module/contact/page/(:var)');

        return $this->view->render('browser', array(
            'title' => 'Contacts',
            'contacts' => $contacts,
            'paginator' => $paginator
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
                   ->appendScript('@Contact/admin/browser.js');

        $this->view->getBreadcrumbBag()->addOne('Contacts');
    }

    /**
     * Saves table configuration
     * 
     * @return string
     */
    public function saveAction()
    {
        if ($this->request->hasPost('order', 'published') && $this->request->isAjax()) {

            // Grab request data
            $published = $this->request->getPost('published');
            $orders = $this->request->getPost('order');

            // Do update
            $contactManager = $this->getContactManager();
            $contactManager->updateOrders($orders);
            $contactManager->updatePublished($published);

            if ($this->request->hasPost('default')) {
                $contactManager->makeDefault($this->request->getPost('default'));
            }

            $this->flashBag->set('success', 'Configuration has been updated successfully');
            return '1';
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
            $this->flashBag->set('success', 'Selected contact has been removed successfully');

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
            $ids = array_keys($this->request->getPost('toDelete'));

            $contactManager = $this->getContactManager();
            $contactManager->deleteByIds($ids);

            $this->flashBag->set('success', 'Selected contacts have been removed successfully');
        } else {
            $this->flashBag->set('warning', 'You should select at least one contact to remove');
        }

        return '1';
    }
}
