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
	 * Updates published state by their associated ids
	 * 
	 * @param array $pair
	 * @return boolean
	 */
	public function updatePublished(array $pair);

	/**
	 * Updates order values by associated ids
	 * 
	 * @param array $orders
	 * @return boolean
	 */
	public function updateOrders(array $orders);

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
	 * Marks contact id as a default one
	 * 
	 * @param string $id Contact id
	 * @return boolean
	 */
	public function makeDefault($id);

	/**
	 * Fetches all contact bags filtered by pagination
	 * 
	 * @param integer $page Current page
	 * @param integer $itemsPerPage Per page count
	 * @return array|boolean
	 */
	public function fetchAllByPage($page, $itemsPerPage);

	/**
	 * Fetches all published contact entities
	 * 
	 * @return array
	 */
	public function fetchAllPublished();

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
	 * @return boolean|\Bono\Stdlib\VirtualBag
	 */
	public function fetchById($id);
}
