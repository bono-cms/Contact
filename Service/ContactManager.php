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

final class ContactManager extends AbstractManager
{
    /**
     * Any compliant contact mapper
     * 
     * @var \Contact\Storage\ContactMapperInterface
     */
    private $contactMapper;

    /**
     * State initialization
     * 
     * @param \Contact\Storage\ContactMapperInterface $contactMapper
     * @return void
     */
    public function __construct(ContactMapperInterface $contactMapper)
    {
        $this->contactMapper = $contactMapper;
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
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings($settings)
    {
        if (isset($settings['default'])) {
            $this->contactMapper->updateDefault((int) $settings['default']);
        }

        unset($settings['default']);
        return $this->contactMapper->updateSettings($settings);
    }

    /**
     * Saves a contact
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function save(array $input)
    {
        $input['contact']['order'] = (int) $input['contact']['order'];
        $input['contact'] = ArrayUtils::arrayWithout($input['contact'], array('makeDefault'));

        return $this->contactMapper->saveEntity($input['contact'], $input['translation']);
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

    /**
     * Deletes a contact by its associated id
     * 
     * @param string|array $id Contact's id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->contactMapper->deleteEntity($id);
    }
}
