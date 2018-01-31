<?php
namespace eduluz1976\ARPC;

/**
 * Description of Mock
 *
 * @author eduardoluz
 */
class Mock {
   
    protected $__sessionID=false;
    protected $__config;
    // On the next opportunity, these values will be sent to server
    protected $__cache;
    
    public static function factory($className, $config=[]) {
        
        $properties = '';
        $methods = '';
        if ($config) {
            
            if (array_key_exists('properties', $config)) {
                $properties = self::buildPropertiesDefs($config['properties']);
            }
            
            if (array_key_exists('methods', $config)) {
                $methods = self::buildMethodsDefs($config['methods']);
            }
        }
        
        $namespace = false;
        $basename = Utils::getClassName($className);
        
        
        if (strlen($basename) != strlen($className)) {
            $namespace = substr($className,0,(-1*strlen($basename))-1);
        }
        
//        echo "\n\n \t === $className \n \t --- $basename \n\t ... $namespace ";
        
        if ($namespace) {
            $classDef = "namespace $namespace;\n class $basename extends \\eduluz1976\\ARPC\\Mock {\n $properties \n $methods \n } ";
        } else {
            $classDef = " class $basename extends \\eduluz1976\\ARPC\\Mock {\n $properties \n $methods \n } ";

            }

        eval($classDef);
    }
    
    
    
    
    public static function buildPropertiesDefs($properties) {
        $ret = '';
        
        foreach ($properties as $name => $value) {
            if (is_numeric($value)) {
                $ret .= "\n protected \$_$name = $value; ";
            } else {
                $ret .= "\n protected \$_$name = '$value'; ";
            }
            
        }
        
        return $ret;
    }
    
    
    
    public static function buildMethodsDefs($methods) {
        $ret = '';
        
        
        return $ret;
    }
    
    
    
    public function __set($name, $value) {
        
        $attrName = $this->__getName($name);
        
        if ( array_key_exists($attrName, get_object_vars($this))) {
            $this->$attrName = $value;
            $this->__cache[$attrName] = $value;            
        } else {
            $this->__cache[$name] = $value;            
        }
        
    }
    
    
    public function __get($name) {
        
        $attrName = $this->__getName($name);
        
        if ( array_key_exists($attrName, get_object_vars($this))) {
            return $this->$attrName;
        }
        return false;
    }
    
    
    protected function __getName($name) {
        return "_$name";
    }
    
    
    
    public function __construct() {
        $this->__config = Proxy::checkCatalog(get_class($this));
    }
    
    
    public function __call($name, $arguments) {
        if (array_key_exists('methods', $this->__config) && array_key_exists($name, $this->__config['methods'])) {
            // 
            echo "\n\n Running the method $name \n";
        } else {
            trigger_error("Method not exists ($name)", E_ERROR);
        }
    }
    
    
}
