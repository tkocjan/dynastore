<?php
/**
 * Storefront_View_Helper_UserInfo
 *
 * Access authentication data saved in the session
 *
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
require_once 'Zend/View/Interface.php';

/**
 * AuthInfo helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class App_View_Helper_Category extends Zend_View_Helper_Abstract
{
    public function category()
    {
        //Logger::info(__METHOD__.': entry');
        $catalogService = new Zstore\Domain\Catalog\CatalogService();
        return $catalogService//->getCached()
                ->getSubCategoriesOfId(null);
    }
}
