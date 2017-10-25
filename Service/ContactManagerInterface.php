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

interface ContactManagerInterface
{
    /**
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings($settings);

    /**
     * Returns last contact's id
     * 
     * @return integer
     */
    public function getLastId();

    /**
     * Returns prepared paginator's instance
     * 
     * @return \Bono\Paginate\Paginator
     */
    public function getPaginator();

    /**
     * Fetches all contacts optionally filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Per page count
     * @param boolean $published Whether to fetch only published or not
     * @return array
     */
    public function fetchAll($published, $page, $itemsPerPage);

    /**
     * Adds a contact
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input);

    /**
     * Updates a contact
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input);

    /**
     * Deletes a contact by its associated id
     * 
     * @param string $id Contact id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * Deletes contacts by their associated ids
     * 
     * @param array $ids Array of contact ids
     * @return boolean
     */
    public function deleteByIds(array $ids);

    /**
     * Fetches contact bag by associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return boolean|\Bono\Stdlib\VirtualEntity
     */
    public function fetchById($id, $withTranslations);
}
