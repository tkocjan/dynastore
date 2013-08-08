<?php
namespace Zstore\Domain\Doctrine;
use Doctrine\ORM\EntityRepository,
    Zstore\Domain\Catalog\CategoryEntity,
    Zstore\Domain\Catalog\ICategoryRepository,
    Exception,
    Logger;

class DocCategoryRepository extends DocBaseRepository implements ICategoryRepository
{
    protected $_primary = 'categoryId';
    
    public function getSubCategoriesOfId($parentId) 
    {    
        //Logger::info(__METHOD__.': $parentId='.var_export($parentId, true));
        
        // this works
        $cats = $this->findBy(array('parent'=>$parentId), array('name' => 'ASC'));
        //proxy & toArray is huge: Logger::info(__METHOD__.': $cats[0]='.print_r($cats[0], true));
        return $cats;
        
        /* this works
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
           ->from('Zstore\Domain\Catalog\CategoryEntity', 'c')
           ->orderBy('c.name');
        if (null === $parentId)
            $qb->where('c.parent IS NULL');
        else {
            $qb->where('c.parent = :parentId')
               ->setParameter('parentId', $parentId);
        }        
        $q=$qb->getQuery();
        Logger::info(__METHOD__.': dql='.print_r($q->getDQL(), true));
        Logger::info(__METHOD__.': sql='.print_r($q->getSQL(), true));        
        $cats = $q->getResult(); 
        return $cats;  
         * 
         */        
    }
    
    public function getCategoryById($id)
    {
        return $this->find($id);
    }

    public function getCategoryByIdent($ident)
    {
        return $this->findOneBy(array('ident'=>$ident));
    }
    
    public function getCategories()
    {
        return $this->findAll();
    }
    
    public function saveData($data, $entity = null){
        Logger::info(__METHOD__.": before: data=".print_r($data, true));
        
        // don't use parentId, use parent 
        $parentId = $data['parentId'];
        $parent = $this->find($parentId);        
        unset($data['parentId']);   // passed by lazy-copy, doesn' effect callee
        $data['parent'] = $parent;
        Logger::info(__METHOD__.": after: data=".print_r($data, true));
        
        return parent::saveData($data, $entity);
        
        /*
        if (is_null($entity)) {
            $entity = new $this->_entityName();
            $this->_em->persist($entity);
        }
        
        $props = $entity->toArray();
        unset($props['categoryId']);    //don't set id
        foreach($props as $prop=>$value) {
            Logger::info(__METHOD__.": prop=".$prop);
            if (array_key_exists($prop, $data)) {
                Logger::info(__METHOD__.": value=".print_r($data[$prop], true));
                $entity->$prop = $data[$prop];
            }
        }
        $this->_em->flush();
         * 
         */
    }
}
