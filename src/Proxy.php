<?php

namespace eduluz1976\ARPC;

/**
 * Description of Proxy
 *
 * @author eduardoluz
 */
class Proxy {
    
    protected $serverList = [];
    
    
    public function registerServer($url,$config=[]) {
        $this->serverList[$url] = $config;
    }
    
    
    public function initAutoloader() {
        
        spl_autoload_register(function ($className) {
            $basename = \eduluz1976\ARPC\Proxy::getClassName($className);
            die("Class $basename not found");
        });
        
        
    }
    
    
    public static function getClassName($className) {
         $lastSlashPos = strrpos($className, '\\');
        if ($lastSlashPos) {
            $basename = substr($className,1+$lastSlashPos);
        } else {
            $basename = $className;
        }
       return $basename;
    } 
    
    
}
