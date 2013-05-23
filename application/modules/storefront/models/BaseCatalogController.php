<?php
class Storefront_Model_BaseCatalogController extends Zend_Controller_Action
{
    /**
     * @var Zstore\Domain\Catalog\CatalogService
     */
    protected $_catalogModel;

    /**
     * @var array
     */
    protected $_forms = array();
    
    public function init()
    {
        $this->_catalogModel = new Zstore\Domain\Catalog\CatalogService();
    }

    protected function _getCategoryForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['addCategory'] = 
                $this->_catalogModel->getForm('catalogCategoryAdd');
        $this->_forms['addCategory']->setAction($urlHelper->url(array(
            'controller' => 'catalog' ,
            'action' => 'savecategory'
            ),
            'admin'
        ));
        $this->_forms['addCategory']->setMethod('post');

        return $this->_forms['addCategory'];
    }

    protected function _getProductForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['addProduct'] = $this->_catalogModel->getForm('catalogProductAdd');
        $this->_forms['addProduct']->setAction($urlHelper->url(array(
            'controller' => 'catalog' ,
            'action' => 'saveproduct'
            ),
            'admin'
        ));
        $this->_forms['addProduct']->setMethod('post');

        return $this->_forms['addProduct'];
    }

    protected function _getProductImageForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['addImage'] = $this->_catalogModel->getForm('catalogProductImageAdd');
        $this->_forms['addImage']->setAction($urlHelper->url(array(
            'controller' => 'catalog' ,
            'action' => 'saveimage'
            ),
            'admin'
        ));
        $this->_forms['addImage']->setMethod('post');

        return $this->_forms['addImage'];
    }
}
