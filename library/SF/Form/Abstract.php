<?php
/**
 * Simple base form class to provide model injection
 *
 * @category   Storefront
 * @package    SF_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Form_Abstract extends Zend_Form
{
    /**
     * @var SF_Model_IService
     */
    protected $_model;

    /**
     * Model setter
     * 
     * @param SF_Model_IService $model 
     */
    public function setModel(SF_Model_IService $model)
    {
        $this->_model = $model;
    }

    /**
     * Model Getter
     * 
     * @return SF_Model_IService 
     */
    public function getModel()
    {
        return $this->_model;
    }
}