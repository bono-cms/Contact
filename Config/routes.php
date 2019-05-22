<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return array(
    '/%s/module/contact/tweak' => array(
        'controller' => 'Admin:Contact@tweakAction',
        'disallow' => array('guest')
    ),
    
    '/%s/module/contact/delete/(:var)' => array(
        'controller' => 'Admin:Contact@deleteAction',
        'disallow' => array('guest')
    ),

    '/%s/module/contact' => array(
        'controller' => 'Admin:Contact@indexAction'
    ),

    '/%s/module/contact/page/(:var)' => array(
        'controller'    => 'Admin:Contact@indexAction'
    ),

    '/%s/module/contact/add' => array(
        'controller' => 'Admin:Contact@addAction'
    ),

    '/%s/module/contact/edit/(:var)' => array(
        'controller' => 'Admin:Contact@editAction'
    ),

    '/%s/module/contact/save' => array(
        'controller' => 'Admin:Contact@saveAction',
        'disallow' => array('guest')
    )
);
