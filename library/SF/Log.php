<?php
class SF_Log {
    static function info($method='noMethod', $msg='noMsg') {
        global $logger;
        $logger->info($method.': '.$msg);
    }
    
    static function debug($method='noMethod', $msg='noMsg') {
        global $logger;
        $logger->debug($method.': '.$msg);
    }
}
