<?php
namespace Zstore\Domain\Doctrine;
use Doctrine\ORM\EntityRepository,
    Zstore\Domain\User\IUserRepository,
    Zstore\Domain\User\UserEntity,
    Logger;

class DocUserRepository extends DocBaseRepository implements IUserRepository
{
    protected $_primary = 'userId';
    
    public function getUserById($id) {
        return $this->find($id);
    }
    
    public function getUserByEmail($email, $ignoreUser=null){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
           ->from('Zstore\Domain\User\UserEntity', 'u')
           ->where('u.email = ?1')
           ->setParameter(1, $email);
        if (null !== $ignoreUser) {
            $qb->andWhere('u.email != ?2')
               ->setParameter(2, $ignoreUser->email);
        }
        $query=$qb->getQuery();
        return $query->getOneOrNullResult();
    }
    
    public function getUsers($paged=null, $pageSize=5, $order=null){
        return $this->findAll();
    }
    
    /*
    public function remove($userId) {
        $user = $this->find($userId);
        $this->_em->remove($user);
        $this->_em->flush();
    }
     * 
     */
    
    /*
    public function saveData($data, $user = null){
        if (is_null($user)) {
            $user = new UserEntity();
            $this->_em->persist($user);
        }
        Logger::info(__METHOD__.": data=".print_r($data, true));

        $props = $user->toArray();
        unset($props['userId']);    //don't set id
        foreach($props as $prop=>$value) {
            Logger::info(__METHOD__.": prop=".$prop);
            if (array_key_exists($prop, $data)) {
                Logger::info(__METHOD__.": value=".$value);
                $user->$prop = $data[$prop];
            }
        }
        $this->_em->flush();
    }
     * 
     */
}
