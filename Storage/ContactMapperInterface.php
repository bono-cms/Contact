<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Contact\Storage;

interface ContactMapperInterface
{
    /**
     * Marks contact ID as a default one
     * 
     * @param string $id Contact ID
     * @return boolean
     */
    public function updateDefault($id);

    /**
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings($settings);

    /**
     * Fetches contact name by its associated id
     * 
     * @param string $id Contact id
     * @return string
     */
    public function fetchNameById($id);

    /**
     * Fetches block data by its associated id
     * 
     * @param string $id Block id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations);

    /**
     * Fetches all contacts optionally filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Per page count
     * @param boolean $published Whether to fetch only published or not
     * @return array
     */
    public function fetchAll($published, $page, $itemsPerPage);
}
