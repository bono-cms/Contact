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
     * History manager to keep tracks
     * 
     * @var \Cms\Service\HistoryManagerInterface
     */
    private $historyManager;

    /**
     * State initialization
     * 
     * @param \Contact\Storage\ContactMapperInterface $contactMapper
     * @param \Cms\Service\HistoryManagerInterface $historyManager
     * @return void
     */
    public function __construct(ContactMapperInterface $contactMapper, HistoryManagerInterface $historyManager)
    {
        $this->contactMapper = $contactMapper;
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
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings($settings)
    {
        return $this->contactMapper->updateSettings($settings);
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
        return $this->contactMapper->updateDefault((int) $id);
    }

    /**
     * {@inheritDoc} 
     */
    protected function toEntity(array $contact)
    {
        $entity = new VirtualEntity();
        $entity->setId($contact['id'], VirtualEntity::FILTER_INT)
               ->setLangId($contact['lang_id'], VirtualEntity::FILTER_INT)
               ->setName($contact['name'], VirtualEntity::FILTER_HTML)
               ->setPhone($contact['phone'], VirtualEntity::FILTER_HTML)
               ->setEmail($contact['email'], VirtualEntity::FILTER_HTML)
               ->setDescription($contact['description'], VirtualEntity::FILTER_HTML)
               ->setOrder($contact['order'], VirtualEntity::FILTER_INT)
               ->setDefault($contact['default'], VirtualEntity::FILTER_BOOL)
               ->setPublished($contact['published'], VirtualEntity::FILTER_BOOL);

        return $entity;
    }

    /**
     * Fetches all contacts optionally filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Per page count
     * @param boolean $published Whether to fetch only published or not
     * @return array
     */
    public function fetchAll($published, $page, $itemsPerPage)
    {
        return $this->prepareResults($this->contactMapper->fetchAll($published, $page, $itemsPerPage));
    }

    /**
     * Adds a contact
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input)
    {
        //$input['order'] = (int) $input['order'];
        //$this->track('Contact "%s" has been added', $input['name']);

        return $this->contactMapper->saveEntity(ArrayUtils::arrayWithout($input['contact'], array('makeDefault')), $input['translation']);
    }

    /**
     * Updates a contact
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        //$input['order'] = (int) $input['order'];

        //$this->track('Contact "%s" has been updated', $input['name']);
        return $this->contactMapper->saveEntity($input['contact'], $input['translation']);
    }

    /**
     * Deletes a contact by its associated id
     * 
     * @param string $id Contact's id
     * @return boolean
     */
    public function deleteById($id)
    {
        //$name = Filter::escape($this->contactMapper->fetchNameById($id));

        if ($this->contactMapper->deleteEntity($id)) {
            //$this->track('Contact "%s" has been removed', $name);
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
        $this->contactMapper->deleteEntity($ids);

        $this->track('Batch removal of %s contacts', count($ids));
        return true;
    }

    /**
     * Fetches contact bag by associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return boolean|\Bono\Stdlib\VirtualEntity
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations == true) {
            return $this->prepareResults($this->contactMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->contactMapper->fetchById($id, false));
        }
    }
}
