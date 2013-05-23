<?php
/** Cms_Resource_Page_Item */
/*
if (!class_exists('Cms_Resource_Page_Item')) {
    require_once dirname(__FILE__) . '/Page/Item.php';
}
 * 
 */

/**
 * Cms_Resource_Page
 *
 * @category   Cms
 * @package    Cms_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Cms_Model_Page_ZendDb_PageRepository 
    extends     SF_Model_Repository 
    implements  Cms_Model_Page_IPageRepository
{
    protected $_name = 'page';
    protected $_primary = 'pageId';
    protected $_rowClass = 'Cms_Model_Page_PageEntity';

    public function getPageById($id)
    {
        return $this->find($id)->current();
    }
}