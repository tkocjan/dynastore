<?php
class Storefront_AdminCatalogController extends Zend_Controller_Action
{
    /**
     * @var Zstore\Domain\Catalog\CatalogService
     */
    protected $_catalogService;

    /**
     * @var array
     */
    protected $_forms = array();
    
    public function init()
    {
        $this->_catalogService = new Zstore\Domain\Catalog\CatalogService();
    }

    /* could delete all acl checks in actions */
    public function preDispatch() {
        SF_Log::debug(__METHOD__, 'entry');
        
        if (!$this->_helper->acl('Admin'))
            return $this->_helper->redirectCommon('gotoLogin');
    }

    public function listAction()
    {
        SF_Log::info(__METHOD__, 'entry');
        
        /*
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }
         * 
         */
        
        $this->view->active='catalog';
        
        $this->view->categorySelect = 
            $this->_catalogService->getForm('catalogCategorySelect');
        $this->view->categorySelect->populate($this->getRequest()->getPost());
        $this->view->categoryId = $this->_getParam('categoryId');

        if ($this->_getParam('categoryId')) {
            $this->view->products = 
                $this->_catalogService->getProductsByCategory(
                    (int) $this->_getParam('categoryId'), 
                    null, null, null, false);
        }
    }

    public function addcategoryAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }

        $this->view->active='catalog';
        
        $this->view->categoryForm = $this->_getCategoryForm();
    }

    public function savecategoryAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }
        
        $this->view->active='catalog';
        
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('addcategory');
        }

        if(false === $this->_catalogService->saveCategory($request->getPost())) {
            $this->view->categoryForm = $this->_getCategoryForm();
            return $this->render('addcategory');
        }

        $redirector = $this->getHelper('redirector');
        return $redirector->gotoRoute(array('action' => 'list'), 'admin');
    }

    public function addproductAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }

        $this->view->active='catalog';
        
        $this->view->productForm = $this->_getProductForm();
    }

    public function saveproductAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }

        $this->view->active='catalog';
        
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('addproduct');
        }

        if(false === 
                ($id = $this->_catalogService->saveProduct($request->getPost()))) {
            $this->view->productForm = $this->_getProductForm();
            return $this->render('addproduct');
        }

        $redirector = $this->getHelper('redirector');
        return $redirector->gotoRoute(array('action' => 'productimages', 
                                            'id' => $id), 
                                      'admin');
    }

    public function productimagesAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }

        $this->view->active='catalog';
        
        if (false === ($id = $this->_getParam('id',false))) {
            throw new SF_Exception('No product id sent');
        }

        $product = $this->_catalogService->getProductById($id);

        if (null === $product) {
            throw new SF_Exception('Unknown product');
        }

        $this->view->product = $product;
        $this->view->imageForm = $this->_getProductImageForm();
        $this->view->imageForm->populate($product->toArray());
    }

    public function saveimageAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }

        $this->view->active='catalog';
        
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('addproduct');
        }

        if (false === ($id = $this->_getParam('productId',false))) {
            throw new SF_Exception('No product id sent');
        }

        $product = $this->_catalogService->getProductById($id);

        if(false === ($id = $this->_catalogService->addProductImage(
                                                    $product, 
                                                    $request->getPost()))) {
            $this->view->product = $product;
            $this->view->imageForm = $this->_getProductImageForm();
            $this->view->imageForm->populate($product->toArray());
            return $this->render('productimages');
        }
        
        $redirector = $this->getHelper('redirector');
        return $redirector->gotoRoute(
                array('action' => 'productimages', 'id' => $product->productId), 
                'admin');
    }

    public function deleteproductAction()
    {
        if (false === ($id = $this->_getParam('id',false))) {
            throw new SF_Exception('Unknown product');
        }

        $this->view->active='catalog';
        
        $this->_catalogService->deleteProduct($id);
        
        $redirector = $this->getHelper('redirector');
        return $redirector->gotoRoute(array('action' => 'list',
                                            'controller' => 'adminCatalog'), 
                                      'admin');
    }
    
    protected function _getCategoryForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['addCategory'] = 
                $this->_catalogService->getForm('catalogCategoryAdd');
        $this->_forms['addCategory']->setAction(
            $urlHelper->url(array('controller' => 'adminCatalog' ,
                                  'action' => 'savecategory'),
                            'admin'));
        $this->_forms['addCategory']->setMethod('post');

        return $this->_forms['addCategory'];
    }

    protected function _getProductForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['addProduct'] = 
            $this->_catalogService->getForm('catalogProductAdd');
        $this->_forms['addProduct']->setAction($urlHelper->url(
                                        array('controller' => 'adminCatalog',
                                              'action' => 'saveproduct'),
                                        'admin'));
        $this->_forms['addProduct']->setMethod('post');

        return $this->_forms['addProduct'];
    }

    protected function _getProductImageForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['addImage'] = 
            $this->_catalogService->getForm('catalogProductImageAdd');
        $this->_forms['addImage']->setAction($urlHelper->url(
                                        array('controller' => 'adminCatalog',
                                              'action' => 'saveimage'),
                                        'admin'));
        $this->_forms['addImage']->setMethod('post');

        return $this->_forms['addImage'];
    }
}
