<?php
class Cms_Model_Page_PageService extends SF_Model_Service
{
    private $pageRepository;
    
    public function __construct($options = null,
                         Cms_Model_Page_IPageRepository $pageRepository = null) {
        parent::__construct($options);
        
        $class = PAGE_REPOSITORY;
        $this->pageRepository = new $class;
        if ($pageRepository)
            $this->pageRepository = $pageRepository;
    }

   public function getPageById($id)
    {
        $id = (int) $id;
        return $this->pageRepository->getPageById($id);
    }
}
