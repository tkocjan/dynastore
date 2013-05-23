<?php
namespace Zstore\Domain\Doctrine;
use Doctrine\ORM\EntityRepository,
    Zstore\Domain\Catalog\ProductEntity,
    Zstore\Domain\Catalog\ProductImageEntity,
    Zstore\Domain\Catalog\IProductRepository,
    Zstore\Domain\Catalog\CatalogService,
    Logger;

class DocProductRepository extends DocBaseRepository implements IProductRepository
{
    protected $catalogService;
    
    public function setCatalogService($catalogService) {
        $this->catalogService = $catalogService;
    }
    
    protected function getCatalogService() {
        if (null === $this->catalogService) {
            $this->catalogService = new CatalogService();
        }
        return $this->catalogService;
    }
            
    /**
     * Get a product by its productId
     *
     * @param int $id The id to search for
     * @return Storefront_Model_Catalog_ProductEntity|null
     */
    public function getProductById($id)
    {
        return $this->find($id);
    }
    
    /**
     * Get a product by its ident string
     *
     * @param string $ident The ident to search for
     * @return Storefront_Model_Catalog_ProductEntity|null
     */
    public function getProductByIdent($ident)
    {
        return $this->findOneBy(array('ident'=>$ident));
    }
    
    /**
     * Get a list of product by their category
     *
     * @param  int|array $categoryId The category id(s)
     * @param  boolean   $paged      Use Zend_Paginator?
     * @param  array     $order      Order results
     * @return Zend_Db_Table_Rowset|Zend_Paginator
     */
    public function getProductsByCategory($categoryId, $paged=null, 
            $pageSize=PRODUCT_PAGE_SIZE, $order=null)
    {
        $sql = 'SELECT p FROM Zstore\Domain\Catalog\ProductEntity p '.
               'WHERE p.category IN (:catIds)';
        $q = $this->_em->createQuery($sql);
        $q->useResultCache(true);
        $q->setParameter('catIds', $categoryId);

        $prods = $q->getResult();
        return $prods;

        /*
        if (true === is_array($order)) {
            $sql .= ' ORDER BY p.:col';
            if ($order[1])
                $sql .= ' 
            
            $select->order($order);
            
        }
         * 
         */
        
        /*
        $select = $this->select();
        $select->from('product')
               ->where("categoryId IN(?)", $categoryId);
        
        if (true === is_array($order)) {
            $select->order($order);
        }
        
        if (null !== $paged) {
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
            $count = clone $select;
            $count->reset(Zend_Db_Select::COLUMNS)
                  ->reset(Zend_Db_Select::FROM)
                  ->from('product', new Zend_Db_Expr(
                              'COUNT(*) AS `zend_paginator_row_count`'));
            $adapter->setRowCount($count);

            $paginator = new Zend_Paginator($adapter);
            $paginator->setItemCountPerPage($pageSize)
                      ->setCurrentPageNumber((int) $paged);
            return $paginator;
        }
        return $this->fetchAll($select);
         * 
         */
    } 
    
    /**
     * Add a product image
     *
     * @param  array $data Data for the entity
     * @return ProductImageEntity
     */
    public function addProductImage($product, $data)
    {
        $image = new ProductImageEntity();
        //$this->_em->persist($image);

        //unset($data['productId']);    // not necessary
        //$data['product'] = $product;  // now set in add image
        $this->populate($data, $image);
        
        Logger::info(__METHOD__.': $images='.print_r($product->images, true));

        //$images = $product->images;     // can't set images[] here
        //$images[] = $image;
        $product->addImage($image);
        
        $this->_em->flush();
        Logger::info(__METHOD__.': $imageId='.$image->imageId);
        Logger::info(__METHOD__.': $imageId='.$image->isDefault);
        Logger::info(__METHOD__.': $productId='.$image->product->productId);

        return $image->imageId;
    }
    
    
    public function saveData($data, $entity = null) 
    {
        //if categoryId set, then find category
        if (isset($data['categoryId'])) {
            $categoryId = $data['categoryId'];
            $catalogService = getCatalogService();
            $data['category'] = $catalogService->getCategoryById($categoryId);
            //unset($data['categoryId']); // not necessary
        }
        return parent::saveData($data, $entity);
    }

    /*
    public function saveData($data, $entity = null){
        if (is_null($entity)) {
            $entity = new $this->_entityName();
            $this->_em->persist($entity);
        }
        Logger::info(__METHOD__.": before: data=".print_r($data, true));
        
        $props = $entity->toArray();
        unset($props['productId']);    //don't set id
        foreach($props as $prop=>$value) {
            Logger::info(__METHOD__.": prop=".$prop);
            if (array_key_exists($prop, $data)) {
                Logger::info(__METHOD__.": value=".print_r($data[$prop], true));
                $entity->$prop = $data[$prop];
            }
        }
        $this->_em->flush();
    }
     * 
     */
}
