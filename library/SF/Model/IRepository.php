<?php
/**
 * SF_Model_Resource_Db_Interface
 * 
 * Provides some Zend db specific methods for our resources
 * that use Zend_Db_Table. This will hopefully protect us from change
 * in the future.
 */
interface SF_Model_IRepository //extends SF_Model_IResource
{
	/** ZF methods */
    //public function info($key);
    //public function createRow(array $data, $defaultSource);
	
	/** SF methods */
	public function saveData($info, $row);
}
