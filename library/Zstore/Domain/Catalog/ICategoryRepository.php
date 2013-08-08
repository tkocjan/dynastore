<?php
namespace Zstore\Domain\Catalog;

/**
 * ICategoryRepository
 * 
 * @category   Storefront
 * @package    Zstore\Domain\Catalog
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface ICategoryRepository 
{
    public function getSubCategoriesOfId($parentId);
    public function getCategoryByIdent($ident);
    public function getCategoryById($id);
    public function getCategories();
}