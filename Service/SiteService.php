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

final class SiteService
{
    /**
     * Contact manager service
     * 
     * @var \Contact\Service\ContactManager
     */
    private $contactManager;

    /**
     * State initialization
     * 
     * @param \Contact\Service\ContactManager $contactManager
     * @return void
     */
    public function __construct(ContactManager $contactManager)
    {
        $this->contactManager = $contactManager;
    }

    /**
     * Returns all contact entities
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->contactManager->fetchAll(true, null, null);
    }
}
