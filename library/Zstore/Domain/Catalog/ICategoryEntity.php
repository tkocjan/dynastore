<?php
namespace Zstore\Domain\Catalog;

/**
 * ICategoryEntity
 * 
 * @category   Storefront
 * @package    Zstore\Domain\Catalog
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface ICategoryEntity
{
    public function getParentCategory();
}
