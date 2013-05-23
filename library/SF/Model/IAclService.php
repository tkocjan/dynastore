<?php
/**
 * SF_Model_Acl_Interface
 *
 * @category   Storefront
 * @package    SF_Model_Acl
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface SF_Model_IAclService
{
    public function setRole($identity);
    public function getRole();
    public function checkAcl($action);
    public function setAcl(SF_Acl_Interface $acl);
    public function getAcl();
}
