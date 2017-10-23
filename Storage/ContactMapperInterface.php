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
     * Updates published state by its associated id
     * 
     * @param string $id Contact id
     * @param string $published Either 0 or 1
     * @return boolean
     */
    public function updatePublishedById($id, $published);

    /**
     * Updates contact's sort order
     * 
     * @param string $id Contact id
     * @param integer $order New order
     * @return boolean
     */
    public function updateOrderById($id, $order);

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
     * Fetches all contacts filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Per page count
     * @return array
     */
    public function fetchAllByPage($page, $itemsPerPage);

    /**
     * Fetches all published contacts
     * 
     * @return array
     */
    public function fetchAllPublished();
}
