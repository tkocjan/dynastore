<?php
/**
 * SF_Service_Authentication
 * 
 * The authentication service provides authentication services for
 * the storefront.
 * 
 * @category   Storefront
 * @package    Storefront_Service
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Service_Authentication 
{
    /**
     * @var Zend_Auth_Adapter_DbTable
     */
    protected $_authAdapter;

    /**
     * @var Zstore\Domain\User\UserService
     */
    protected $_userService;

    /**
     * @var Zend_Auth
     */
    protected $_auth;

    /**
     * Construct 
     * 
     * @param null|Zstore\Domain\User\UserService $userModel 
     */
    public function __construct(
            Zstore\Domain\User\UserService $userService = null)
    {
        $this->_userService = null === $userService 
                ? new Zstore\Domain\User\UserService() : $userService;
    }

    /**
     * Authenticate a user
     *
     * @param  array $credentials Matched pair array containing email/passwd
     * @return boolean
     */
    public function authenticate($credentials)
    {        
        $adapter = $this->getAuthAdapter($credentials);
        $auth    = $this->getAuth();
        $result  = $auth->authenticate($adapter);

        if (!$result->isValid()) {
            return false;
        }
        
        //$auth->getIdentity() only returns email address at this point
        //  store entire user record as identity
        $user = $adapter->getResultRowObject();
        SF_Log::info(__METHOD__, 'user='.print_r($user, true));
        $auth->getStorage()->write($user);
        
        return true;
    }

    public function getAuth()
    {
        if (null === $this->_auth) {
            $this->_auth = Zend_Auth::getInstance();
        }
        return $this->_auth;
    }

    public function getIdentity()
    {
        $auth = $this->getAuth();
        if ($auth->hasIdentity()) {
            return $auth->getIdentity();
        }
        return false;
    }
    
    /**
     * Clear any authentication data
     */
    public function clear()
    {
        $this->getAuth()->clearIdentity();
    }
    
    /**
     * Set the auth adpater.
     *
     * @param Zend_Auth_Adapter_Interface $adapter
     */
    public function setAuthAdapter(Zend_Auth_Adapter_Interface $adapter)
    {
        $this->_authAdapter = $adapter;
    }
    
    /**
     * Get and configure the auth adapter
     * 
     * @param  array $value Array of user credentials
     * @return Zend_Auth_Adapter_DbTable
     */
    public function getAuthAdapter($values)
    {
        if (null === $this->_authAdapter) {
            $authAdapter = new Zend_Auth_Adapter_DbTable(
                Zend_Db_Table_Abstract::getDefaultAdapter(),
                'user',
                'email',
                'passwd',
                'SHA1(CONCAT(?,salt))'
            );
            $this->setAuthAdapter($authAdapter);
            $this->_authAdapter->setIdentity($values['email']);
            $this->_authAdapter->setCredential($values['passwd']);
        }
        return $this->_authAdapter;
    }
}
