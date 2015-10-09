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
	 * Checks whether contact id is a default one
	 * 
	 * @param string $id Contact id
	 * @return boolean
	 */
	private function isDefault($id)
	{
		//@TODO
		return false;
		d($this->getDefaults());
	}

	/**
	 * {@inheritDoc} 
	 */
	protected function toEntity(array $contact)
	{
		$entity = new VirtualEntity();
		$entity->setId((int) $contact['id'])
			   ->setName(Filter::escape($contact['name']))
			   ->setPhone(Filter::escape($contact['phone']))
			   ->setEmail(Filter::escape($contact['email']))
			   ->setDescription(Filter::escape($contact['description']))
			   ->setOrder((int) $contact['order'])
			   ->setDefault((bool) $this->isDefault($entity->getId()))
			   ->setPublished((bool) $contact['published']);

		return $entity;
	}

	/**
	 * Fetches dummy contact entity
	 * 
	 * @return \Bono\Stdlib\VirtualEntity
	 */
	public function fetchDummy()
	{
		return $this->toEntity(array(
			'id' => null,
			'name' => null,
			'phone' => null,
			'email' => null,
			'description' => null,
			'order' => null,
			'published' => true
		));
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
	 * Adds a contact
	 * 
	 * @param array $input Raw form data
	 * @return boolean
	 */
	public function add(array $input)
	{
		$this->track('Contact "%s" has been added', $input['name']);
		return $this->contactMapper->insert($input);
	}

	/**
	 * Updates a contact
	 * 
	 * @param array $form Raw form data
	 * @return boolean
	 */
	public function update(array $input)
	{
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