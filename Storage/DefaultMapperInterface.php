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

interface DefaultMapperInterface
{
	/**
	 * Checks whether target contact id was marked as default
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public function isDefault($id);

	/**
	 * Fetches all defaults
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Inserts default contact id
	 * 
	 * @param string $contactId
	 * @return boolean
	 */
	public function insert($contactId);

	/**
	 * Updates default id
	 * 
	 * @param string $id Contact id
	 * @return boolean
	 */
	public function update($id);

	/**
	 * Checks whether a default was defined for contact
	 * 
	 * @return boolean
	 */
	public function exists();
}
