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

use Krystal\Stdlib\VirtualEntity;
use Krystal\Validate\Pattern;
use Cms\Controller\Admin\AbstractController;

final class Contact extends AbstractController
{
    /**
     * Renders a grid
     * 
     * @param integer $page Current page
     * @return string
     */
    public function gridAction($page = 1)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->appendScript('@Contact/admin/browser.js');

        // Append a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Contacts');

        $contactManager = $this->getModuleService('contactManager');
        $contacts = $contactManager->fetchAllByPage($page, $this->getSharedPerPageCount());

        $paginator = $contactManager->getPaginator();
        $paginator->setUrl('/admin/module/contact/page/(:var)');

        return $this->view->render('browser', array(
            'contacts' => $contacts,
            'paginator' => $paginator
        ));
    }

    /**
     * Saves grid configuration
     * 
     * @return string
     */
    public function tweakAction()
    {
        if ($this->request->hasPost('order', 'published') && $this->request->isAjax()) {

            // Grab request data
            $published = $this->request->getPost('published');
            $orders = $this->request->getPost('order');

            // Do update
            $contactManager = $this->getModuleService('contactManager');
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
     * Returns a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $contact
     * @return string
     */
    private function createForm(VirtualEntity $contact, $title)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->appendScript('@Contact/admin/contact.form.js');

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Contacts', 'Contact:Admin:Contact@gridAction')
                                       ->addOne($title);

        return $this->view->render('contact.form', array(
            'contact' => $contact
        ));
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        $contact = new VirtualEntity();
        $contact->setPublished(true);

        return $this->createForm($contact, 'Add a contact');
    }

    /**
     * Renders edit form
     * 
     * @param string $id
     * @return string
     */
    public function editAction($id)
    {
        $contact = $this->getModuleService('contactManager')->fetchById($id);

        if ($contact !== false) {
            return $this->createForm($contact, 'Edit the contact');
        } else {
            return false;
        }
    }

    /**
     * Deletes a contact or a collection of contacts
     * 
     * @return string
     */
    public function deleteAction()
    {
        $contactManager = $this->getModuleService('contactManager');

        // Batch removal
        if ($this->request->hasPost('toDelete')) {
            $ids = array_keys($this->request->getPost('toDelete'));

            $contactManager->deleteByIds($ids);
            $this->flashBag->set('success', 'Selected contacts have been removed successfully');
        } else {
            $this->flashBag->set('warning', 'You should select at least one contact to remove');
        }

        // Single removal
        if ($this->request->hasPost('id') && $this->request->isAjax()) {
            $id = $this->request->getPost('id');

            $contactManager->deleteById($id);
            $this->flashBag->set('success', 'Selected contact has been removed successfully');
        }

        return '1';
    }

    /**
     * Persists a contact
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('contact');

        $formValidator = $this->validatorFactory->build(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'email' => new Pattern\Email()
                )
            )
        ));

        if ($formValidator->isValid()) {
            $contactManager = $this->getModuleService('contactManager');

            // If id is empty, then do add, otherwise do update
            if ($input['id']) {
                if ($contactManager->update($input)) {
                    $this->flashBag->set('success', 'The contact has been updated successfully');
                    return '1';
                }

            } else {
                if ($contactManager->add($input)) {
                    $this->flashBag->set('success', 'A contact has been added successfully');
                    return $contactManager->getLastId();
                }
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
