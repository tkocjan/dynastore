<?php
/**
 * SF_Model_Acl_Abstract
 *
 * Base model class for models that have acl support
 *
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class SF_Model_AclService 
         extends SF_Model_Service 
         implements SF_Model_IAclService, Zend_Acl_Resource_Interface
{
    /**
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * @var Zend_Acl_Role_Interface
     */
    protected $_role;

    /**
     * Set the identity of the current request
     *
     * @param array|string|null|Zend_Acl_Role_Interface $identity
     * @return SF_Model_Service
     */
    public function setRole($identity)
    {
        if (is_object($identity))
            $identity = (array)$identity;

        if (is_array($identity)) {
            if (!isset($identity['role'])) {
                $identity['role'] = 'Guest';
            }
            $role = new Zend_Acl_Role($identity['role']);
        } elseif (is_scalar($identity) && !is_bool($identity)) {
            $role = new Zend_Acl_Role($identity);
        } elseif (null === $identity) {
            $role = new Zend_Acl_Role('Guest');
        } elseif (!$identity instanceof Zend_Acl_Role_Interface) {
            throw new SF_Model_Exception('Invalid identity provided');
        }
        $this->_role = $role;
        return $this;
    }

    /**
     * Get the role, if no ident use guest
     *
     * @return string
     */
    public function getRole()
    {
        if (null === $this->_role) {
            $auth = Zend_Auth::getInstance();
            if (!$auth->hasIdentity()) {
                return 'Guest';
            }
            $this->setRole($auth->getIdentity());
        }
        return $this->_role;
    }

    /**
     * Check the acl
     *
     * @param string $action
     * @return boolean
     */
    public function checkAcl($action)
    {
        return $this->getAcl()->isAllowed(
            $this->getRole(),
            $this,
            $action
        );
    }
}
