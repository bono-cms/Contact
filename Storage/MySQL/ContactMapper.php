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
use Krystal\Db\Sql\RawSqlFragment;

final class ContactMapper extends AbstractMapper implements ContactMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_contact');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return ContactTranslationMapper::getTableName();
    }

    /**
     * Returns columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::column('id'),
            self::column('order'),
            self::column('published'),
            self::column('default'),
            ContactTranslationMapper::column('lang_id'),
            ContactTranslationMapper::column('name'),
            ContactTranslationMapper::column('phone'),
            ContactTranslationMapper::column('email'),
            ContactTranslationMapper::column('description')
        );
    }

    /**
     * Marks contact ID as a default one
     * 
     * @param string $id Contact ID
     * @return boolean
     */
    public function updateDefault($id)
    {
        // Data to be updated
        $data = array(
            'default' => new RawSqlFragment('CASE WHEN id = '.$id.' THEN 1 ELSE 0 END')
        );

        return $this->db->update(self::getTableName(), $data)
                        ->execute();
    }

    /**
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings($settings)
    {
        return $this->updateColumns($settings, array('published', 'order'));
    }

    /**
     * Fetches contact data by its associated id
     * 
     * @param string $id Contact ID
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findEntity($this->getColumns(), $id, $withTranslations);
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
        $db = $this->createEntitySelect($this->getColumns())
                   // Language ID constraint
                   ->whereEquals(ContactTranslationMapper::column('lang_id'), $this->getLangId());

        if ($published === true) {
            $db->andWhereEquals(self::column('published'), 1)
               ->orderBy(new RawSqlFragment(sprintf('%s, CASE WHEN %s = 0 THEN %s END DESC', self::column('order'), self::column('order'), ContactTranslationMapper::column('id'))));
        } else {
            $db->orderBy(self::column('id'))
               ->desc();
        }

        if ($page !== null && $itemsPerPage !== null) {
            $db->paginate($page, $itemsPerPage);
        }

        return $db->queryAll();
    }
}
