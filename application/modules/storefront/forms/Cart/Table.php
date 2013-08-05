<?php
/**
 * Base of the cart table where users edit delete from their cart,
 * elements are dynamically added for the qty fields in
 * Zend_View_Helper_Cart::cartTable
 *
 * @see Zend_View_Helper_Cart::cartTable
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Form_Cart_Table extends SF_Form_Abstract
{
    public function init()
    {
        //setDecorators below should prevent defaults from loading
        $this->setDisableLoadDefaultDecorators(true);

        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'cart/_cart.phtml')),
            'Form'
        ));

        $this->setMethod('post');
        $this->setAction('');

        $this->addElement('submit', 'update-cart', array(
            'decorators' => array(
                'ViewHelper'
            ),
            'label' => 'Update',
            'attribs' => array('class'=>'btn'),
        ));
        $this->addElement('submit', 'submit_my_order', array(
            'decorators' => array(
                'ViewHelper'
            ),
            'label' => 'Submit Order',
            'attribs' => array('class'=>'btn btn-primary'),
        ));
    }
}
