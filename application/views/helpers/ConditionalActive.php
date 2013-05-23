<?php
/**
 * ConditionalActive view helper
 *
 */
class App_View_Helper_ConditionalActive extends Zend_View_Helper_Abstract
{   
    public $view;   //this is set by abstract
    
    function conditionalActive($key) {
        
        if ($key == 'admin') {
            if ($this->view->active == 'customer' || 
                    $this->view->active == 'catalog')
                return 'active';
            else 
                return '';
        }

        if ($this->view->active == $key) 
            return 'active';
        else 
            return '';

        //return ($view->active == $key)?'active':'';
    }
}
