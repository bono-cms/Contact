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
        // Append a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Contacts');

        $contactManager = $this->getModuleService('contactManager');
        $contacts = $contactManager->fetchAllByPage($page, $this->getSharedPerPageCount());

        $paginator = $contactManager->getPaginator();
        $paginator->setUrl($this->createUrl('Contact:Admin:Contact@gridAction', array(), 1));

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
     * @param string $id
     * @return string
     */
    public function deleteAction($id)
    {
        return $this->invokeRemoval('contactManager', $id);
    }

    /**
     * Persists a contact
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('contact');

        return $this->invokeSave('contactManager', $input['id'], $input, array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'email' => new Pattern\Email()
                )
            )
        ));
    }
}
