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

    '/admin/module/contact/tweak' => array(
        'controller' => 'Admin:Contact@tweakAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/contact/delete' => array(
        'controller' => 'Admin:Contact@deleteAction',
        'disallow' => array('guest')
    ),

    '/admin/module/contact' => array(
        'controller' => 'Admin:Contact@gridAction'
    ),

    '/admin/module/contact/page/(:var)' => array(
        'controller'    => 'Admin:Contact@gridAction'
    ),

    '/admin/module/contact/add' => array(
        'controller' => 'Admin:Contact@addAction'
    ),

    '/admin/module/contact/edit/(:var)' => array(
        'controller' => 'Admin:Contact@editAction'
    ),

    '/admin/module/contact/save' => array(
        'controller' => 'Admin:Contact@saveAction',
        'disallow' => array('guest')
    )
);
