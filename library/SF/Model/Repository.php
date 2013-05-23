<?php
/**
 * SF_Model_Resource_Db_Table_Abstract
 * 
 * Provides some common db functionality that is shared
 * across our db-based resources.
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class SF_Model_Repository 
    extends Zend_Db_Table_Abstract 
    implements SF_Model_IRepository
{
    /**
     * Save a row to the database
     *
     * @param array             $data The data to insert/update
     * @param Zend_DB_Table_Row $row Optional The row to use
     * @return mixed The primary key
     */
    public function saveData($data, $row = null)
    {
        if (null === $row) {
            $row = $this->createRow();
        }
        
        foreach ($this->info('cols') as $column) {
            if (array_key_exists($column, $data)) {
                $row->$column = $data[$column];
            }
        }
        
        return $row->save();
    }
}
