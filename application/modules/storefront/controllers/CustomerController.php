<?php
/**
 * CustomerController
 * 
 * @category   Storefront
 * @package    Storefront_Controllers
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_CustomerController extends Zend_Controller_Action 
{
    protected $_userService;
    protected $_authService;
    protected $_forms;
    
    public function init()
    {
        $this->view->active = 'customer';
        
        // get the default services
        $this->_userService = new Zstore\Domain\User\UserService();
        $this->_authService = new SF_Service_Authentication();

        // add forms
        $this->view->registerForm = $this->getRegistrationForm();
        $this->view->loginForm = $this->getLoginForm();
        $this->view->userForm = $this->getUserForm();
    }
    
    public function indexAction() 
    {
        if (!$this->_userService->checkAcl('updateUser')) {
            Logger::info(__METHOD__.": checkAcl failed");
            return $this->_helper->redirectCommon('gotoLogin');
        }
        
        $this->view->user = 
            $this->_userService->getUserById(
                $this->_authService->getIdentity()->userId);

        if (null === $this->view->user) {
            throw new SF_Exception('Unknown user');
        }

        $this->view->active = 'register';
        $this->view->userForm = 
                $this->getUserForm()->populate($this->view->user->toArray());
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        $redirector = $this->_helper->getHelper('Redirector');

        $validator = 'edit';
        $onFail = 'edit';
        $onSuccess = array(
            'urlOptions' => array(
                'controller' => 'customer',
                'action' => 'index'
            ),
            'route' => 'default'
        );

        if ($this->_getParam('isAdmin')) {
            $this->view->user =
                $this->_userService->getUserById($this->_getParam('id'));
            $this->view->userForm = $this->getUserAdminForm();

            $validator = 'admin';
            $onFail = 'edit';
            $onSuccess = array(
                'urlOptions' => array(
                    'controller' => 'customer',
                    'action' => 'list'
                ),
                'route' => 'admin'
            );
        }

        if (false === $this->_userService->saveUser($request->getPost(), $validator)) {
            return $this->render($onFail);
        }

        return $redirector->gotoRoute(
            $onSuccess['urlOptions'], $onSuccess['route']);
    }

    public function registerAction() {
        $this->view->active='register';
    }
	
    public function completeRegistrationAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('register');
        }

        $this->view->active='register';
        
        if (false === $this->_userService->registerUser($request->getPost())) {
            return $this->render('register');
        }
    }
	
    public function listAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }
        
        $this->view->active='customer';

        $this->view->users = $this->_userService->getUsers();
    }

    public function editAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }
        
        $this->view->active='customer';

        $this->view->userForm = $this->getUserAdminForm();
        $this->view->user = $this->_userService->getUserById($this->_getParam('id'));
        $this->view->userForm->populate($this->view->user->toArray());
    }

    public function deleteAction()
    {
        if (false === ($id = $this->_getParam('id',false))) {
            throw new SF_Exception('Unknown user');
        }

        $this->view->active='customer';

        $this->_userService->deleteUser($id);

        $redirector = $this->getHelper('redirector');
        return $redirector->gotoRoute(array('action' => 'list'), 'admin');
    }
	
    public function loginAction() {
        $this->view->active='login';
    }

    public function authenticateAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('login');
        }

        // Validate
        $form = $this->_forms['login'];
        if (!$form->isValid($request->getPost())) {
            return $this->render('login');
        }
        
        if (false === $this->_authService->authenticate($form->getValues())) {
            $form->setDescription('Login failed, please try again.');
            return $this->render('login');
        }
        
        return $this->_helper->redirector('index');
    }
	
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index');
    }
    
    public function getRegistrationForm()
    {
        $urlHelper = $this->_helper->getHelper('url');
        
        $this->_forms['register'] = $this->_userService->getForm('userRegister');
        $this->_forms['register']->setAction($urlHelper->url(array(
            'controller' => 'customer' , 
            'action' => 'complete-registration'
            ), 
            'default'
        ));
        $this->_forms['register']->setMethod('post');
        
        return $this->_forms['register'];
    }

    public function getUserForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['userEdit'] = $this->_userService->getForm('userEdit');
        $this->_forms['userEdit']->setAction($urlHelper->url(array(
            'controller' => 'customer' ,
            'action' => 'save'
            ),
            'default'
        ));
        $this->_forms['userEdit']->setMethod('post');

        return $this->_forms['userEdit'];
    }

    public function getUserAdminForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['userAdmin'] = $this->_userService->getForm('userAdmin');
        $this->_forms['userAdmin']->setAction($urlHelper->url(array(
            'controller' => 'customer' ,
            'action' => 'save'
            ),
            'admin'
        ));
        $this->_forms['userAdmin']->setMethod('post');

        return $this->_forms['userAdmin'];
    }
    
    public function getLoginForm()
    {
        $urlHelper = $this->_helper->getHelper('url');
        
        $this->_forms['login'] = $this->_userService->getForm('userLogin');
        $this->_forms['login']->setAction($urlHelper->url(array(
            'controller' => 'customer',
            'action'     => 'authenticate',
            ), 
            'default'
        ));
        $this->_forms['login']->setMethod('post');
        
        return $this->_forms['login'];
    }
}
