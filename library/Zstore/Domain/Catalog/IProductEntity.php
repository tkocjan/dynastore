<?php
namespace Zstore\Domain\Catalog;

/**
 * IProductEntity
 * 
 * @category   Storefront
 * @package    Zstore\Domain\Catalog
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface IProductEntity
{
    public function getImages($includeDefault=false);
    public function getDefaultImage();
    public function getPrice($withDiscount=true,$withTax=true);
    public function isDiscounted();
    public function isTaxable();
}