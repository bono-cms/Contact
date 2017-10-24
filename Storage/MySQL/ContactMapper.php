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
            self::getFullColumnName('id'),
            self::getFullColumnName('order'),
            self::getFullColumnName('published'),
            ContactTranslationMapper::getFullColumnName('lang_id'),
            ContactTranslationMapper::getFullColumnName('name'),
            ContactTranslationMapper::getFullColumnName('phone'),
            ContactTranslationMapper::getFullColumnName('email'),
            ContactTranslationMapper::getFullColumnName('description')
        );
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
     * Fetches block data by its associated id
     * 
     * @param string $id Block id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findEntity($this->getColumns(), $id, $withTranslations);
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
        return $this->createEntitySelect($this->getColumns())
                    ->whereEquals(ContactTranslationMapper::getFullColumnName('lang_id'), $this->getLangId())
                    ->orderBy(self::getFullColumnName('id'))
                    ->desc()
                    ->paginate($page, $itemsPerPage)
                    ->queryAll();
    }

    /**
     * Fetches all published contacts
     * 
     * @return array
     */
    public function fetchAllPublished()
    {
        return $this->createEntitySelect($this->getColumns())
                    ->whereEquals(ContactTranslationMapper::getFullColumnName('lang_id'), $this->getLangId())
                    ->orderBy(new RawSqlFragment('`order`, CASE WHEN `order` = 0 THEN `id` END DESC'))
                    ->queryAll();
    }
}
