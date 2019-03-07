<?php

/**
 * Module configuration container
 */

return array(
    'caption'  => 'Contact',
    'description' => 'Contact module allows you to manage contact information on your site',
    'menu' => array(
        'name'  => 'Contact',
        'icon' => 'fas fa-phone-square',
        'items' => array(
            array(
                'route' => 'Contact:Admin:Contact@gridAction',
                'name' => 'View all contacts'
            ),
            array(
                'route' => 'Contact:Admin:Contact@addAction',
                'name' => 'Add new contact'
            )
        )
    )
);