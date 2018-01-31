<?php

namespace eduluz1976\ARPC;

/**
 * Description of Proxy
 *
 * @author eduardoluz
 */
class Proxy {
    
    protected static $serverList = [];
    protected static $catalog = [];
    
    
    public static function loadCatalog() {
        
        self::$catalog = [
            "Something\\MyClassTest" => ['properties' => ['name'=>'','price'=>17], 'methods'=>['printAll'=>[]]],
            "MyClassTest" => ['properties' => ['name'=>'','price'=>22], 'methods'=>['printAll'=>[]]]
            ];        
    }
    
    public static function registerServer($url,$config=[]) {
        self::$serverList[$url] = $config;
        // update catalog...
    }
    
    
    public function initAutoloader() {
        
        spl_autoload_register(function ($className) {
            
            try {
                $config = Proxy::checkCatalog($className);
                Mock::factory($className, $config);
            } catch (\Exception $ex) {
                
            }            
        });
        
        
    }
    
    
    
    public static function checkCatalog($className) {
        
       // echo "\n\n\t *** $className \n";
        
        if (array_key_exists($className, self::$catalog)) {
            return self::$catalog[$className];
        } else {
            throw new \Exception('class not found',-1);
        }
    }
    
    
}
