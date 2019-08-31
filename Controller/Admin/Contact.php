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
    public function indexAction($page = 1)
    {
        // Append a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Contacts');

        $contactManager = $this->getModuleService('contactManager');
        $contacts = $contactManager->fetchAll(false, $page, $this->getSharedPerPageCount());

        $paginator = $contactManager->getPaginator();
        $paginator->setUrl($this->createUrl('Contact:Admin:Contact@indexAction', array(), 1));

        return $this->view->render('index', array(
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
        // Do update
        $contactManager = $this->getModuleService('contactManager');
        $contactManager->updateSettings($this->request->getPost());

        $this->flashBag->set('success', 'Configuration has been updated successfully');
        return '1';
    }

    /**
     * Renders a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity|array $contact
     * @return string
     */
    private function createForm($contact, $title)
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Contacts', 'Contact:Admin:Contact@indexAction')
                                       ->addOne($title);

        return $this->view->render('form', array(
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
        $contact = $this->getModuleService('contactManager')->fetchById($id, true);

        if ($contact !== false) {
            $name = $this->getCurrentProperty($contact, 'name');
            return $this->createForm($contact, $this->translator->translate('Edit the contact "%s"', $name));
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
        $historyService = $this->getService('Cms', 'historyManager');
        $service = $this->getModuleService('contactManager');

        // Batch removal
        if ($this->request->hasPost('batch')) {
            $ids = array_keys($this->request->getPost('batch'));

            $service->deleteByIds($ids);
            $this->flashBag->set('success', 'Selected elements have been removed successfully');

            // Save in the history
            $historyService->write('Contact', 'Batch removal of %s contacts', count($ids));

        } else {
            $this->flashBag->set('warning', 'You should select at least one element to remove');
        }

        // Single removal
        if (!empty($id)) {
            $contact = $this->getModuleService('contactManager')->fetchById($id, false);

            $service->deleteById($id);
            $this->flashBag->set('success', 'Selected element has been removed successfully');

            // Save in the history
            $historyService->write('Contact', 'Contact "%s" has been removed', $contact->getName());
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
        $input = $this->request->getPost();

        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'email' => new Pattern\Email()
                )
            )
        ));

        if (1) {
            $service = $this->getModuleService('contactManager');
            $historyService = $this->getService('Cms', 'historyManager');

            // Current page name
            $name = $this->getCurrentProperty($this->request->getPost('translation'), 'name');

            // Save in the history
            $service->save($input);

            // Update
            if (!empty($input['contact']['id'])) {
                $this->flashBag->set('success', 'The element has been updated successfully');

                $historyService->write('Contact', 'Contact "%s" has been updated', $name);
                return '1';

            } else {
                // Create
                $this->flashBag->set('success', 'The element has been created successfully');

                $historyService->write('Contact', 'Contact "%s" has been added', $name);
                return $service->getLastId();
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
