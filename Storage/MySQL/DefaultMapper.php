<?php/** * This file is part of the Bono CMS *  * Copyright (c) No Global State Lab *  * For the full copyright and license information, please view * the license file that was distributed with this source code. */namespace Contact\Storage\MySQL;use Cms\Storage\MySQL\AbstractMapper;use Contact\Storage\DefaultMapperInterface;final class DefaultMapper extends AbstractMapper implements DefaultMapperInterface{	/**	 * {@inheritDoc}	 */	protected $table = 'bono_module_contact_defaults';	/**	 * Checks whether target contact id was marked as default	 * 	 * @param string $id	 * @return boolean	 */	public function isDefault($id)	{		return $this->db->select()						->count('1', 'count')						->from($this->table)						->whereEquals('contact_id', $id)						->query('count') > 0;	}	/**	 * Fetches all defaults	 * 	 * @return array	 */	public function fetchAll()	{		return $this->db->select('*')						->from($this->table)						->queryAll();	}	/**	 * Inserts default contact id	 * 	 * @param string $contactId	 * @return boolean	 */	public function insert($contactId)	{		return $this->db->insert($this->table, array(			'lang_id' => $this->getLangId(),			'contact_id' => $contactId		))->execute();	}	/**	 * Updates default id	 * 	 * @param string $id Contact id	 * @return boolean	 */	public function update($id)	{		return $this->db->update($this->table, array('contact_id' => $id))						->whereEquals('lang_id', $this->getLangId())						->execute();	}	/**	 * Checks whether a default was defined for contact	 * 	 * @return boolean	 */	public function exists()	{		return $this->db->select()						->count('lang_id', 'count')						->from($this->table)						->query('count') > 0;	}}