<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Contact\Service;

use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Contact\Storage\ContactMapperInterface;
use Contact\Storage\DefaultMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Security\Filter;

final class ContactManager extends AbstractManager implements ContactManagerInterface
{
    /**
     * Any compliant contact mapper
     * 
     * @var \Contact\Storage\ContactMapperInterface
     */
    private $contactMapper;

    /**
     * Any compliant contact default mapper
     * 
     * @var \Contact\Storage\DefaultMapperInterface
     */
    private $defaultMapper;

    /**
     * History manager to keep tracks
     * 
     * @var \Cms\Service\HistoryManagerInterface
     */
    private $historyManager;

    /**
     * State initialization
     * 
     * @param \Contact\Storage\ContactMapperInterface $contactMapper
     * @param \Contact\Storage\DefaultMapperInterface $defaultMapper
     * @param \Cms\Service\HistoryManagerInterface $historyManager
     * @return void
     */
    public function __construct(ContactMapperInterface $contactMapper, DefaultMapperInterface $defaultMapper, HistoryManagerInterface $historyManager)
    {
        $this->contactMapper = $contactMapper;
        $this->defaultMapper = $defaultMapper;
        $this->historyManager = $historyManager;
    }

    /**
     * Tracks activity
     * 
     * @param string $message
     * @param string $placeholder
     * @return boolean
     */
    private function track($message, $placeholder)
    {
        return $this->historyManager->write('Contact', $message, $placeholder);
    }

    /**
     * Updates published state by their associated ids
     * 
     * @param array $pair
     * @return boolean
     */
    public function updatePublished(array $pair)
    {
        foreach ($pair as $id => $published) {
            if (!$this->contactMapper->updatePublishedById($id, $published)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Updates order values by associated ids
     * 
     * @param array $orders
     * @return boolean
     */
    public function updateOrders(array $orders)
    {
        foreach ($orders as $id => $order) {
            if (!$this->contactMapper->updateOrderById($id, $order)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns last contact's id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->contactMapper->getLastId();
    }

    /**
     * Returns prepared paginator's instance
     * 
     * @return \Bono\Paginate\Paginator
     */
    public function getPaginator()
    {
        return $this->contactMapper->getPaginator();
    }

    /**
     * Marks contact id as a default one
     * 
     * @param string $id Contact id
     * @return boolean
     */
    public function makeDefault($id)
    {
        if ($this->defaultMapper->exists()) {
            return $this->defaultMapper->update($id);
        } else {
            return $this->defaultMapper->insert($id);
        }
    }

    /**
     * Returns default associations with language ids
     * 
     * @return array
     */
    private function getDefaults()
    {
        static $defaults = null;

        if (is_null($defaults)) {
            $defaults = $this->defaultMapper->fetchAll();
        }

        return $defaults;
    }

    /**
     * {@inheritDoc} 
     */
    protected function toEntity(array $contact)
    {
        $entity = new VirtualEntity();
        $entity->setId($contact['id'], VirtualEntity::FILTER_INT)
               ->setName($contact['name'], VirtualEntity::FILTER_HTML)
               ->setPhone($contact['phone'], VirtualEntity::FILTER_HTML)
               ->setEmail($contact['email'], VirtualEntity::FILTER_HTML)
               ->setDescription($contact['description'], VirtualEntity::FILTER_HTML)
               ->setOrder($contact['order'], VirtualEntity::FILTER_INT)
               ->setDefault($this->defaultMapper->isDefault($entity->getId()), VirtualEntity::FILTER_BOOL)
               ->setPublished($contact['published'], VirtualEntity::FILTER_BOOL);

        return $entity;
    }

    /**
     * Fetches all contact bags filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Per page count
     * @return array|boolean
     */
    public function fetchAllByPage($page, $itemsPerPage)
    {
        return $this->prepareResults($this->contactMapper->fetchAllByPage($page, $itemsPerPage));
    }

    /**
     * Fetches all published contact entities
     * 
     * @return array
     */
    public function fetchAllPublished()
    {
        return $this->prepareResults($this->contactMapper->fetchAllPublished());
    }

    /**
     * Adds a contact
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input)
    {
        $input['order'] = (int) $input['order'];

        $this->track('Contact "%s" has been added', $input['name']);
        return $this->contactMapper->insert(ArrayUtils::arrayWithout($input, array('makeDefault')));
    }

    /**
     * Updates a contact
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        $input['order'] = (int) $input['order'];

        $this->track('Contact "%s" has been updated', $input['name']);
        return $this->contactMapper->update($input);
    }

    /**
     * Deletes a contact by its associated id
     * 
     * @param string $id Contact's id
     * @return boolean
     */
    public function deleteById($id)
    {
        $name = Filter::escape($this->contactMapper->fetchNameById($id));

        if ($this->contactMapper->deleteById($id)) {
            $this->track('Contact "%s" has been removed', $name);
            return true;

        } else {
            return false;
        }
    }

    /**
     * Deletes contacts by their associated ids
     * 
     * @param array $ids Array of contact ids
     * @return boolean
     */
    public function deleteByIds(array $ids)
    {
        foreach ($ids as $id) {
            if (!$this->contactMapper->deleteById($id)) {
                return false;
            }
        }

        $this->track('Batch removal of %s contacts', count($ids));
        return true;
    }

    /**
     * Fetches contact bag by associated id
     * 
     * @param string $id
     * @return boolean|\Bono\Stdlib\VirtualEntity
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->contactMapper->fetchById($id));
    }
}
