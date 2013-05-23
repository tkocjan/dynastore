<?php
/**
 * Cms_Model_Acl_Cms
 *
 * @category   Storefront
 * @package    Storefront_Model_Acl
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Cms_Model_Acl_Cms extends Zend_Acl implements SF_Acl_Interface
{
    /**
     * Add the roles to the acl and deny all by default
     */
    public function __construct()
    {
        // Define roles:
        $this->addRole('Guest')
             ->addRole('Customer', 'Guest')
             ->addRole('Admin', 'Customer');

        // Deny privileges by default; i.e., create a whitelist
        $this->deny();
    }
}