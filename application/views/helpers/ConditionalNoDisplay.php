<?php
/**
 * conditionNoDisplay view helper
 *
 */
class App_View_Helper_ConditionalNoDisplay extends Zend_View_Helper_Abstract
{
    public $view;   //this is set by abstract
    const noDisplay = 'noDisplay';
    
    /* framework calls this to set view */
    //public function setView(Zend_View_Interface $view) {$this->view = $view;}
    
    function conditionalNoDisplay($key) {
        
        switch($key) {
        case 'login':
        case 'register':
            if ($this->view->authInfo()->isLoggedIn()) return self::noDisplay; 
            break;
        case 'logout':
            if (!$this->view->authInfo()->isLoggedIn()) return self::noDisplay; 
            break;
        case 'admin':
            if ('Admin' != $this->view->AuthInfo('role')) return self::noDisplay; 
            break;
        }

        return '';
    }
}
