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
	 * Fetches contact data by its associated id
	 * 
	 * @param string $id Contact id
	 * @return array
	 */
	public function fetchById($id);

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

	/**
	 * Deletes a contact by its associated id
	 * 
	 * @param string $id Contact id
	 * @return boolean
	 */
	public function deleteById($id);

	/**
	 * Inserts a contact
	 * 
	 * @param array $data Contact data
	 * @return boolean
	 */
	public function insert(array $data);

	/**
	 * Updates contact data
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function update(array $data);
}
