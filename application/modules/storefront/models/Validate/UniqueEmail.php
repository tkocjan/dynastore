<?php
class Storefront_Validate_UniqueEmail extends Zend_Validate_Abstract
{
    const EMAIL_EXISTS = 'emailExists';

    protected $_messageTemplates = array(
        self::EMAIL_EXISTS => 'Email "%value%" already exists in our system',
    );

    public function __construct(Zstore\Domain\User\UserService $service)
    {
        $this->_service = $service;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);
        $currentUser = isset($context['userId']) ? $this->_service->getUserById($context['userId']) : null;
        $user = $this->_service->getUserByEmail($value, $currentUser);
        
        if (null === $user) {
            return true;
        }

        $this->_error(self::EMAIL_EXISTS);
        return false;
    }
}
