<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Contact\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Contact\Storage\ContactMapperInterface;

final class ContactMapper extends AbstractMapper implements ContactMapperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getTableName()
	{
		return 'bono_module_contact_records';
	}

	/**
	 * Updates published state by its associated id
	 * 
	 * @param string $id Contact id
	 * @param string $published Either 0 or 1
	 * @return boolean
	 */
	public function updatePublishedById($id, $published)
	{
		return $this->updateColumnByPk($id, 'published', $published);
	}

	/**
	 * Updates contact's sort order
	 * 
	 * @param string $id Contact id
	 * @param integer $order New order
	 * @return boolean
	 */
	public function updateOrderById($id, $order)
	{
		return $this->updateColumnByPk($id, 'order', $order);
	}

	/**
	 * Fetches contact name by its associated id
	 * 
	 * @param string $id Contact id
	 * @return string
	 */
	public function fetchNameById($id)
	{
		return $this->findColumnByPk($id, 'name');
	}

	/**
	 * Fetches contact data by its associated id
	 * 
	 * @param string $id Contact id
	 * @return array
	 */
	public function fetchById($id)
	{
		return $this->findByPk($id);
	}

	/**
	 * Fetches all contacts filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array
	 */
	public function fetchAllByPage($page, $itemsPerPage)
	{
		return $this->db->select('*')
						->from(self::getTableName())
						->whereEquals('lang_id', $this->getLangId())
						->orderBy('id')
						->desc()
						->paginate($page, $itemsPerPage)
						->queryAll();
	}

	/**
	 * Deletes a contact by its associated id
	 * 
	 * @param string $id Contact id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->deleteByPk($id);
	}

	/**
	 * Adds a contact
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function insert(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}

	/**
	 * Updates contact data
	 * 
	 * @param array $input Raw input data
	 * @return boolean
	 */
	public function update(array $input)
	{
		return $this->persist($this->getWithLang($input));
	}
}
