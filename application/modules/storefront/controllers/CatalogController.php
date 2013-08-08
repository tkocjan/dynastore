<?php
use Doctrine\Common\Util\Debug;

class Storefront_CatalogController extends Zend_Controller_Action
{
    /**
     * @var Zstore\Domain\Catalog\CatalogService
     */
    protected $_catalogService;
    
    public function init()
    {
        //Logger::info(__METHOD__.': apc='.print_r(apc_sma_info(), true));
        
        $this->_catalogService = new Zstore\Domain\Catalog\CatalogService();
        
        /*
        //mongodb
        $m = new MongoClient();

        // select a database
        $db = $m->comedy;

        // select a collection (analogous to a relational database's table)
        $collection = $db->cartoons;

        // add a record
        //$document = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
        //$collection->insert($document);

        // add another record, with a different "shape"
        //$document = array( "title" => "XKCD", "online" => true );
        //$collection->insert($document);

        // find everything in the collection
        $cursor = $collection->find();

        // iterate through the results
        foreach ($cursor as $document) {
            Logger::info(__METHOD__.': mongo='.$document["title"]);
        }
         * 
         */

        /*
        //below is for doctrine testing
        $userClassname = '\Zstore\Domain\User\UserEntity';
        $productClassname = '\Zstore\Domain\Catalog\ProductEntity';
        $categoryClassname = '\Zstore\Domain\Catalog\CategoryEntity';        

        $doctrine = Zend_Registry::get('doctrine');
        $em = $doctrine->getEntityManager();
        $userRepository = 
                $em->getRepository($userClassname);
        $productRepository = 
                $em->getRepository($productClassname);
        $categoryRepository = 
                $em->getRepository($categoryClassname);

        $users = $userRepository->findAll();
        $user = $users[0];
        Logger::info(__METHOD__.': get_class($user)='.get_class($user));
        Logger::info(__METHOD__.': $user='.var_export($user, true));
        Logger::info(__METHOD__.': $user.toArray()='.print_r($user->toArray(), true));
        
        $user = $userRepository->findOneBy(array('userId' => 9999));
        //Logger::info(__METHOD__.': get_class($user9999)='.get_class($user));
        Logger::info(__METHOD__.': $user9999='.var_export($user, true));

        //Logger::info(__METHOD__.': $users='.print_r($users, true));
        
        $product = $productRepository->find(4);
        Logger::info(__METHOD__.': get_class($product)='.get_class($product));
        Logger::info(__METHOD__.': $product->name='.$product->name);
        //Logger::info(__METHOD__.': $products='.print_r($products, true));

        //$category = $product->getCategory();
        $category = $product->category;
        Logger::info(__METHOD__.': get_class($category)='.get_class($category));
        //Logger::info(__METHOD__.': $category->name='.$category->getName());
        Logger::info(__METHOD__.': $category->name='.$category->name);
        Logger::info(__METHOD__.': $category->ident='.$category->ident);
        $array = $category->toArray();
        $keys = array_keys($array);
        Logger::info(__METHOD__.': $keys='.print_r($keys, true));
                
        $parent=$category->parent;
        if (is_null($parent))
            Logger::info(__METHOD__.': $category->parent is null');
        else {
            Logger::info(__METHOD__.': get_class($category->parent)='.get_class($parent));
            Logger::info(__METHOD__.': $category->parent->name='.$parent->name);
        }
                
        //$image = $product->images[0]; //only gets non-default images
        $images = $product->getImages(true);
        $image = $images[0];
        Logger::info(__METHOD__.': get_class($image)='.get_class($image));
        Logger::info(__METHOD__.': $image->isDefault='.$image->isDefault);
        foreach($images as $image) {
            Logger::info(__METHOD__.': $image->full='.$image->full);
            $isDefault =$image->isDefault;
            Logger::info(__METHOD__.': $image->isDefault()='.var_export($image->isDefault(), true));
        }
        
        $em->flush();
        $em->clear();
        //Logger::info(__METHOD__.': users='.print_r($users, true));
        
        $userRef = $em->getReference($userClassname, 1);
        Logger::info(__METHOD__.': get_class($userRef)='.get_class($userRef));
        Logger::info(__METHOD__.': $userRef->getTitle()='.$userRef->title);
         * 
         */
    }

    public function dynatopcatsAction()
    {
        Logger::info(__METHOD__.': entry');
        
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $topCats = $this->_catalogService//->getCached()
                ->getSubCategoriesOfId(null);

        echo $this->catsToHtml($topCats);
    }

    public function dynaproductsAction()
    {
        Logger::info(__METHOD__.': entry');
        
        $this->_helper->layout->disableLayout();
        
        $categoryIdent = $this->_getParam('categoryIdent');
        $page = $this->_getParam('page');
        $pageSize = $this->_getParam('pageSize');
        $order = $this->_getParam('order');
        $returnto = $this->_getParam('returnto');
        
        $this->view->products = $this->_catalogService->//getCached('product')->
            getProductsByCategory(
                $categoryIdent, $page, $pageSize, $order
            );
        $this->view->categoryIdent = $categoryIdent;
        $this->view->returnto = $returnto;
    }
    
    public function ajaxcategoryAction()
    {
        Logger::info(__METHOD__.': entry');
        
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        header('Content-Type: application/json');
        
        $retData = new stdClass();
        
        $category = $this->_catalogService->//getCached('category')->
            getCategoryByIdent($this->_getParam('categoryIdent', ''));
        if (null === $category) {
            throw new SF_Exception_404('Unknown category ' . 
                $this->_getParam('categoryIdent'));
        }
        $retData->catName = $this->view->escape($category->name);
        
        $topCats = $this->_catalogService//->getCached()
                ->getSubCategoriesOfId(null);

        $retData->topCats = $this->catsToHtml($topCats);

        //Logger::info(__METHOD__.': $categoryId='.var_export($category->categoryId, true));        
        $subCats = $this->_catalogService->//getCached('category')->
            getSubCategoriesOfId($category->categoryId);        
        
        if (count($subCats) == 0) {
            $retData->subCats = '';
        }
        else {
            $retData->subCats =
                '<h3>in this <span>category</span></h3><ul>'.
                $this->catsToHtml($subCats).
                '</ul>';
        }
        
        $this->view->bread = $this->getBreadcrumb($category);
        $retData->bread = $this->view->breadcrumb();
        
        echo json_encode( $retData );
    }
    
    protected function catsToHtml($categories)
    {
        $html = '';
        foreach ($categories as $category) {
            $url = $this->view->url(
                        array('categoryIdent' => $category->ident), 
                        'catalog_category', true );
            $html .= 
                "<li><a href='$url'>$category->name</a></li>";            
        }
        return $html;
    }

    public function indexAction()
    {
        //Logger::info(__METHOD__.': entry');
        
        /*
        $products = $this->_catalogService->//getCached('product')->
            getProductsByCategory(
                $this->_getParam('categoryIdent', 0),
		$this->_getParam('page', 1),
                PRODUCT_PAGE_SIZE,
                array('name')
            );
         * 
         */
        $products = new StdClass();
        $products->categoryIdent = $this->_getParam('categoryIdent', 0);
        $products->page = $this->_getParam('page', 1);
        $products->pageSize = PRODUCT_PAGE_SIZE;
        $products->order = array('name');
        $start = strlen($this->view->baseUrl());        
        $products->returnto = substr($this->view->url(), $start+1);
        
        $this->view->products = $products;

        /*
        $categoryIdent = $this->_getParam('categoryIdent', '');

        $category = $this->_catalogService->//getCached('category')->
            getCategoryByIdent($this->_getParam('categoryIdent', ''));
        if (null === $category) {
            throw new SF_Exception_404('Unknown category ' . 
                $this->_getParam('categoryIdent'));
        }

        //Logger::info(__METHOD__.': $categoryId='.var_export($category->categoryId, true));        
        $subs = $this->_catalogService->//getCached('category')->
            getSubCategoriesOfId($category->categoryId);
        $bread = $this->getBreadcrumb($category);
        
        $this->view->assign(array(
            'category' => $category,
            'subCategories' => $subs,
            'products' => $products,
            'bread' => $bread,
            'categoryIdent' => $categoryIdent,
        ));
         * 
         */
    }
    
    public function viewAction()
    {
        $this->view->product = $this->_catalogService->getProductByIdent(
                $this->_getParam('productIdent', 0));
        
        if (null === $this->view->product) {
            throw new SF_Exception_404(
                    'Unknown product ' . $this->_getParam('productIdent'));
        }
        
        $category = $this->_catalogService->getCategoryByIdent(
                $this->_getParam('categoryIdent', ''));
        $this->view->bread = $this->getBreadcrumb($category);
        
        $start = strlen($this->view->baseUrl());        
        $this->view->returnto = substr($this->view->url(), $start+1);        
    }
    
    public function getBreadcrumb($category)
    {
        return $this->_catalogService->
                getParentCategories($category);
    }
}
