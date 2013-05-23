<?php
namespace Zstore\Domain\User;

/**
 * IUserRepository
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface IUserRepository 
    extends \SF_Model_IRepository 
{
    public function getUserById($id);
    public function getUserByEmail($email, $ignoreUser=null);
    public function getUsers($paged=false, $order=null);
}
