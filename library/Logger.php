<?php
class Logger {
    static function info($msg) {
        global $logger;
        $logger->info($msg);
    }
    
    static function debug($msg) {
        global $logger;
        $logger->debug($msg);
    }
}
