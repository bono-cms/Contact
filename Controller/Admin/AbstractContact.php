<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Contact\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;

abstract class AbstractContact extends AbstractController
{
    /**
     * Returns configured validator instance
     * 
     * @param array $input Raw input data
     * @return \Krystal\Validate\ValidatorChain
     */
    final protected function getValidator(array $input)
    {
        return $this->validatorFactory->build(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'email' => new Pattern\Email()
                )
            )
        ));
    }

    /**
     * Returns contact manager
     * 
     * @return \Contact\Service\ContactManager
     */
    final protected function getContactManager()
    {
        return $this->moduleManager->getModule('Contact')->getService('contactManager');
    }

    /**
     * Loads shared plugins
     * 
     * @return void
     */
    final protected function loadSharedPlugins()
    {
        $this->view->getPluginBag()
                   ->appendScript($this->getWithAssetPath('/admin/contact.form.js'));
    }

    /**
     * Returns template path
     * 
     * @return string
     */
    final protected function getTemplatePath()
    {
        return 'contact.form';
    }

    /**
     * Returns shared variables for Add and Edit controllers
     * 
     * @param array $overrides
     * @return array
     */
    final protected function getSharedVars(array $overrides)
    {
        $this->view->getBreadcrumbBag()->add(array(
            array(
                'name' => 'Contacts',
                'link' => 'Contact:Admin:Browser@indexAction'
            ),
            array(
                'name' => $overrides['title'],
                'link' => '#'
            )
        ));

        $vars = array(
        );

        return array_replace_recursive($vars, $overrides);
    }
}
