<?php
namespace Zstore\Domain\Catalog;

/**
 * IProductRepository
 * 
 * @category   Storefront
 * @package    Zstore\Domain\Catalog
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface IProductRepository
{
    public function getProductById($id);
    public function getProductByIdent($ident);
    public function getProductsByCategory($categoryId, $paged=null, $order=null);
}
