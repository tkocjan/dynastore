<?php
namespace Zstore\Domain\Catalog;

use Logger;

/**
 * \Storefront_Catalog
 * 
 * @category   Storefront
 * @package    \Storefront_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class CatalogService
    extends    \SF_Model_AclService 
    implements \Zend_Acl_Resource_Interface
{
    //protected $_acl;      //from \SF_Model_AclService
    //protected $_role;
    
    protected $categoryRepository;
    protected $productRepository;
    protected $categoryClassname = '\Zstore\Domain\Catalog\CategoryEntity';        
    protected $productClassname = '\Zstore\Domain\Catalog\ProductEntity';        
    protected $productImageRepository;
    
    public function __construct($options = null,
                         $categoryRepository = null,
                         $productRepository = null)
    {      
        parent::__construct($options);
        if ($categoryRepository)
            $this->categoryRepository = $categoryRepository;
        else {
            $doctrine = \Zend_Registry::get('doctrine');
            $em = $doctrine->getEntityManager();
            $this->categoryRepository =
                    $em->getRepository($this->categoryClassname);
        }
        if ($productRepository)
            $this->productRepository = $productRepository;        
        else {
            $doctrine = \Zend_Registry::get('doctrine');
            $em = $doctrine->getEntityManager();
            $this->productRepository = 
                $em->getRepository($this->productClassname);
        }        
    }
    
    /**
     * Get categories
     *
     * @param int $id The parentId
     * @return \Zend_Db_Table_Rowset
     */
    public function getSubCategoriesOfId($parentId)
    {
        //$parentID = (int) $parentID;
        
        return $this->categoryRepository->
                getSubCategoriesOfId($parentId);
    }
    
    /**
     * Get category by ident
     *
     * @param string $ident The ident string
     * @return \Storefront_Model_Catalog_CategoryEntity|null
     */
    public function  getCategoryByIdent($ident)
    {
        return $this->categoryRepository->getCategoryByIdent($ident);
    }

    public function getCategories()
    {
        return $this->categoryRepository->getCategories();
    }

    public function getCategoryById($id)
    {
        $id = (int) $id;
        
        return $this->categoryRepository->getCategoryById($id);
    }
    
    /**
     * Get a product by its id
     *
     * @param  int $id The id
     * @return \Storefront_Model_Catalog_ProductEntity
     */
    public function getProductById($id)
    {
        $id = (int) $id;
        
        return $this->productRepository->getProductById($id);
    }
    
    /**
     * Get a product by its ident
     *
     * @param  string $ident The ident
     * @return \Storefront_Model_Catalog_ProductEntity
     */
    public function getProductByIdent($ident)
    {        
        return $this->productRepository->getProductByIdent($ident);
    }
    
    /**
     * Get products in a category
     *
     * @param int|string  $category The category name or id
     * @param int|boolean $paged    Whether to page results
     * @param array       $order    Order results
     * @param boolean     $deep     Get all products below this category?
     * @return \Zend_Db_Table_Rowset|\Zend_Paginator|null
     */
    public function getProductsByCategory($category, $paged=null, 
            $pageSize=PRODUCT_PAGE_SIZE, $order=null, $deep=true)
    {
        //Logger::info(__METHOD__.': $category='.$category);
        //Logger::info(__METHOD__.': $deep='.$deep);
        if (is_string($category)) {
            $cat = $this->categoryRepository->getCategoryByIdent($category);
            $categoryId = $cat->categoryId;
        } else {
            $categoryId = $category;
        }
        
        if (true === $deep) {
            $ids = $this->getCategoryChildrenIds($categoryId, true);
            $ids[] = $categoryId;
            $categoryId = null === $ids ? $categoryId : $ids;
        }
        
        return $this->productRepository->
                getProductsByCategory($categoryId, $paged, $pageSize, $order);
    }
    
    /**
     * Get a categories children categoryId values
     *
     * @param int     $categoryId The category to get children from
     * @param boolean $recursive  Get the entire category branch?
     * @return array An array of ids
     */
    public function getCategoryChildrenIds($categoryId, $recursive = false)
    {
        $categories = $this->getSubCategoriesOfId($categoryId);
        $cats = array();
               
        foreach ($categories as $category) {
            $cats[] = $category->categoryId;
            
            if (true === $recursive) {
                $cats = array_merge($cats, 
                    $this->getCategoryChildrenIds($category->categoryId, true));
            }
        }

        return $cats;
    }
    
    /**
     * Get a categories parents
     * 
     * @param \Storefront_Model_Catalog_CategoryEntity $category
     * @param boolean Append the parent to the cats array?
     * @return array
     */
    public function getParentCategories($category, $appendParent = true)
    {
        $cats = $appendParent ? array($category) : array();

        if (null === $category->parent) {
            return $cats;
        }

        $parent = $category->getParentCategory();
        $cats[] = $parent;

        if (null !== $parent->parent) {
            $cats = array_merge($cats, 
                    $this->getParentCategories($parent, false));
        }

        return $cats;
    }

    /**
     * Save a category
     * 
     * @param array $data
     * @param string $validator
     * @return int|false
     */
    public function saveCategory($data, $validator = null)
    {
        if (!$this->checkAcl('saveCategory')) {
            throw new \SF_Acl_Exception("Insufficient rights");
        }

        if (null === $validator) {
            $validator = 'add';
        }

        $validator = $this->getForm('catalogCategory' . ucfirst($validator));

        if (!$validator->isValid($data)) {
            return false;
        }

        $data = $validator->getValues();

        return $this->categoryRepository->saveData($data);
    }

    /**
     * Save a product
     * 
     * @param array $data
     * @param string $validator
     * @return int|false
     */
    public function saveProduct($data, $validator = null)
    {
        if (!$this->checkAcl('saveProduct')) {
            throw new \SF_Acl_Exception("Insufficient rights");
        }
        
        if (null === $validator) {
            $validator = 'add';
        }

        $validator = $this->getForm('catalogProduct' . ucfirst($validator));

        if (!$validator->isValid($data)) {
            return false;
        }

        $data = $validator->getValues();
        
        $data['category'] = $this->getCategoryById($data['categoryId']);
        unset($data['categoryId']);

        $primary = $this->productRepository->saveData($data);
        return $primary;

    }

    /**
     * Add a product image
     * 
     * @param \Storefront_Model_Catalog_ProductEntity $product
     * @param array $data
     * @param string $validator
     * @return int|false
     */
    public function addProductImage(
           IProductEntity $product, $data, $validator = null)
    {
        if (!$this->checkAcl('saveProductImage')) {
            throw new \SF_Acl_Exception("Insufficient rights");
        }

        if (null === $validator) {
            $validator = 'add';
        }

        $validator = $this->getForm(
                'catalogProductImage' . ucfirst($validator));

        if (!$validator->isValid($data)) {
            return false;
        }

        // get post data
        $data = $validator->getValues();

        $imageId = $this->productRepository->addProductImage($product,$data);

        return $imageId;
    }

    public function deleteProduct($product)
    {
        if (!$this->checkAcl('deleteProduct')) {
            throw new \SF_Acl_Exception("Insufficient rights");
        }

        if ($product instanceof IProductEntity) {
            $productId = (int) $product->productId;
        } else {
            $productId = (int) $product;
        }

        $product = $this->getProductById($productId);

        if (null === $product)
            return false;
        
        $this->productRepository->remove($product);
        return true;
    }

    /**
     * Implement the \Zend_Acl_Resource_Interface, make this model
     * an acl resource
     *
     * @return string The resource id
     */
    public function getResourceId()
    {
        return 'Catalog';
    }

    /**
     * Injector for the acl, the acl can be injected either directly
     * via this method or by passing the 'acl' option to the models
     * construct.
     *
     * We add all the access rule for this resource here, so we
     * add $this as the resource, plus its rules.
     *
     * @param \SF_Acl_Interface $acl
     * @return \SF_Model_Service
     */
    public function setAcl(\SF_Acl_Interface $acl)
    {
        if (!$acl->has($this->getResourceId())) {
            $acl->add($this)
                ->allow('Admin', $this);
        }
        $this->_acl = $acl;
        return $this;
    }

    /**
     * Get the acl and automatically instantiate the default acl if one
     * has not been injected.
     *
     * @return \Zend_Acl
     */
    public function getAcl()
    {
        if (null === $this->_acl) {
            $this->setAcl(new \Storefront_Model_Acl_Storefront());
        }
        return $this->_acl;
    }
}
