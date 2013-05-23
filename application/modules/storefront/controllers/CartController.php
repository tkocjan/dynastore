<?php
/**
 * Cart Controller
 *
 * @category   Storefront
 * @package    Storefront_Controllers
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_CartController extends Zend_Controller_Action
{
    protected $_cartService;
    protected $_catalogService;

    public function init()
    {
        $this->_cartService = new Storefront_Model_Cart_CartService();
        $this->_catalogService = new Zstore\Domain\Catalog\CatalogService();
    }

    public function addAction()
    {
        $product = $this->_catalogService->getProductById($this->_getParam('productId'));

        if (null === $product) {
            throw new SF_Exception('Product could not be added to cart as it does not exist');
        }

        $this->_cartService->addItem($product, $this->_getParam('qty'));

        //$return = rtrim($this->getRequest()->getBaseUrl(), '/') .
        //    $this->_getParam('returnto');
        $return = $this->_getParam('returnto');
        $redirector = $this->getHelper('redirector');

        return $redirector->gotoUrl($return);
    }

    public function viewAction()
    {
        $this->view->cartModel = $this->_cartService;
    }

    public function updateAction()
    {
        global $logger;
        foreach($_REQUEST as $id=>$value) {
            $logger->info('param '.$id.'='.print_r($value, true));
        }
        
        foreach($this->_getParam('quantity') as $id => $value) {
            $product = $this->_catalogService->getProductById($id);
            if (null !== $product) {
                $this->_cartService->addItem($product, $value);
            }
        }

        /* Should really get from the shippingModel! */
        $this->_cartService->setShippingCost($this->_getParam('shipping'));

        return $this->_helper->redirector('view');
    }

    public function removeAction(){}
}
