<?php
class Storefront_Validate_UniqueIdent extends Zend_Validate_Abstract
{
    const IDENT_EXISTS = 'identExists';

    protected $_messageTemplates = array(
        self::IDENT_EXISTS => 'Ident "%value%" already exists in our system',
    );

    protected $_service;
    protected $_method;

    public function __construct(Zstore\Domain\User\UserService $service, $method)
    {
        $this->_service  = $service;
        $this->_method = $method;

        if (!method_exists($this->_service, $method)) {
            throw new SF_Exception('Method ' . $method . 'does not exist in model');
        }
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        $found = call_user_func(array($this->_service, $this->_method), $value);
        
        if (null === $found) {
            return true;
        }

        $this->_error(self::IDENT_EXISTS);
        return false;
    }
}
