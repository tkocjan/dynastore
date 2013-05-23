<?php
namespace Zstore\Domain\Catalog;
use Doctrine\ORM\Mapping as ORM,
    Zstore\Domain\Entity,
    Zstore\Domain\Catalog\ICategoryEntity;

/** @ORM\Entity(repositoryClass="Zstore\Domain\Doctrine\DocCategoryRepository")
 *  @ORM\Table(name="category") */
class CategoryEntity extends Entity implements ICategoryEntity
{    
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") @var integer */
    protected $categoryId;

    /** @ORM\Column(type="string") @var string */
    protected $name;
    
    /** can use both field & assoc ORM\Column(type="integer") var integer */
    //protected $parentId;
    
    // ManyToOne self mapping
    // ORM\ManyToOne(targetEntity="Zstore\Domain\Catalog\CategoryEntity")
    /** @ORM\ManyToOne(targetEntity="CategoryEntity")
     *  @ORM\JoinColumn(name="parentId", referencedColumnName="categoryId") 
     *  @var CategoryEntity */
    protected $parent;
    
    /** @ORM\Column(type="string") @var string */
    protected $ident;
    
    // declare so Id cannot be set
    // declare as public for proxy generation
    public function _get__categoryId() {
        return $this->categoryId;
    }

    public function getParentCategory() {
        return $this->parent;
    }
    
    // to fix parentId == 0, should be null
    public function getParent() {
        throw new Exception(__METHOD__.': not implemented');
        
        /*
        $parentId = $this->getParentId();
        \Logger::info(__METHOD__.': $parentId='.$parentId);
        if ($parentId === 0) {
            \Logger::info(__METHOD__.': returning null');
            return null;
        }
        else
         * 
         */
            return $this->parent;
    }
}
