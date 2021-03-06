<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Contact;

use Cms\AbstractCmsModule;
use Contact\Service\ContactManager;
use Contact\Service\SiteService;

final class Module extends AbstractCmsModule
{
    /**
     * {@inheritDoc}
     */
    public function getServiceProviders()
    {
        $contactMapper = $this->getMapper('/Contact/Storage/MySQL/ContactMapper');
        $contactManager = new ContactManager($contactMapper);

        return array(
            'contactManager' => $contactManager,
            'siteService' => new SiteService($contactManager)
        );
    }
}
