<?php

namespace Zstore\Domain\Doctrine\Proxy\__CG__\Zstore\Domain\Catalog;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class ProductEntity extends \Zstore\Domain\Catalog\ProductEntity implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function _get__productId()
    {
        $this->__load();
        return parent::_get__productId();
    }

    public function _get__images()
    {
        $this->__load();
        return parent::_get__images();
    }

    public function _set__images($value)
    {
        $this->__load();
        return parent::_set__images($value);
    }

    public function _get__defaultImage()
    {
        $this->__load();
        return parent::_get__defaultImage();
    }

    public function getImages($includeDefault = false)
    {
        $this->__load();
        return parent::getImages($includeDefault);
    }

    public function getDefaultImage()
    {
        $this->__load();
        return parent::getDefaultImage();
    }

    public function addImage($image)
    {
        $this->__load();
        return parent::addImage($image);
    }

    public function _get__price()
    {
        $this->__load();
        return parent::_get__price();
    }

    public function _set__price($value)
    {
        $this->__load();
        return parent::_set__price($value);
    }

    public function getPrice($withDiscount = true, $withTax = true)
    {
        $this->__load();
        return parent::getPrice($withDiscount, $withTax);
    }

    public function isDiscounted()
    {
        $this->__load();
        return parent::isDiscounted();
    }

    public function isTaxable()
    {
        $this->__load();
        return parent::isTaxable();
    }

    public function __get($prop)
    {
        $this->__load();
        return parent::__get($prop);
    }

    public function __set($prop, $value)
    {
        $this->__load();
        return parent::__set($prop, $value);
    }

    public function __isset($prop)
    {
        $this->__load();
        return parent::__isset($prop);
    }

    public function __unset($prop)
    {
        $this->__load();
        return parent::__unset($prop);
    }

    public function toArray()
    {
        $this->__load();
        return parent::toArray();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'productId', 'ident', 'name', 'description', 'shortDescription', 'price', 'discountPercent', 'taxable', 'category', 'images');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}