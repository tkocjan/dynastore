<?php
namespace Zstore\Domain\Doctrine;
use Doctrine\ORM\EntityRepository,
    Zstore\Domain\IRepository,
    Logger;

abstract class DocBaseRepository extends EntityRepository implements IRepository
{
    public function remove($entity) {
        //$entity = $this->find($id);
        $this->_em->remove($entity);
        $this->_em->flush();
    }
    
    public function populate($data, $entity)
    {
        Logger::info(__METHOD__.': before: data='.print_r($data, true));
        $props = $entity->toArray();
        $idFieldName = $this->getIdFieldName($entity);
        Logger::info(__METHOD__.': $idFieldName='.$idFieldName);
        
        unset($props[$idFieldName]);    //don't set id
        foreach($props as $prop=>$value) {
            Logger::info(__METHOD__.': prop='.$prop);
            if (array_key_exists($prop, $data)) {
                Logger::info(__METHOD__.': value='.print_r($data[$prop], true));
                $entity->$prop = $data[$prop];
            }
        }        
    }
    
    public function saveData($data, $entity = null) 
    {
        if (is_null($entity)) {
            $entity = new $this->_entityName();
            $this->_em->persist($entity);
        }
        
        $this->populate($data, $entity);
        
        $this->_em->flush();
        
        $id = $this->getIdFieldName();
        return $entity->$id;
    }
    
    public function getIdFieldName($entity = null)
    {
        if (null === $entity)
            $entityName = $this->_entityName;
        else
            $entityName = get_class($entity);
        $id = $this->_em->getClassMetadata($entityName)->getIdentifier();
        Logger::info(__METHOD__.': $id='.print_r($id, true));
        
        if (is_array($id))
            $id = $id[0];
        return $id;
    }
}
