<?php

namespace eduluz1976\ARPC;

/**
 * Description of Request
 *
 * @author eduardoluz
 */
class Request {

    const HEADER_OBJECT_ID = 'X-ARPC-OID';
    const HEADER_SESSION_ID = 'X-ARPC-SID';
    const HEADER_USER_AGENT = 'ARPC';
    
    
    protected $operation;
    protected $className;
    protected $method;
    protected $parameters;
    protected $OID;
    protected $SID;
    
    
    public function getOperation() {
        return $this->operation;
    }
    
    public function setOperation($op) {
        $this->operation = $op;
        return $this;
    }
    
    public function getClassName() {
        return $this->className;
    }
    
    public function setClassName($name) {
        $this->className = $name;
        return $this;
    }
    
    public function getMethod() {
        return $this->method;
    }
    
    public function setMethod($met) {
        $this->method = $met;
        return $this;
    }
    
    public function getParameters() {
        return $this->parameters;
    }
    
    public function setParameters($parms) {
        $this->parameters = $parms;
        return $this;
    }
    
    public function getOID() {
        return $this->OID;
    }
    
    public function setOID($uid) {
        $this->OID = $uid;
        return $this;
    }
    
    public function getSID() {
        return $this->SID;
    }
    
    public function setSID($sid) {
        $this->SID = $sid;
        return $this;
    }
    
    
    public static function authorize($headers) {
        
        if (array_key_exists('User-Agent', $headers)) {
            $userAgent = $headers['User-Agent'];
            $toCompare = substr($userAgent,0,strlen(self::HEADER_USER_AGENT));

            if ($toCompare !== self::HEADER_USER_AGENT ) {
                throw new AuthorizationException('Unknown client',1000);
            }
        } else {
            
           
        }
    
    }
    
    
    public static function build() {
        
        $headers = getallheaders();
        
        self::authorize($headers);
        
        $request = $_SERVER['REQUEST_URI'];
        
        $first = strpos($request,'/',1);
        
        $operation = substr($request,1,$first-1);
        $target = substr($request,$first+1);
        
        $last = strrpos($target,'/',1);
        
        $class = substr($target,0,$last);
        $method = substr($target,$last+1);
        
        $obj = new Request();
        $obj->setOperation($operation)
                ->setClassName($class)
                ->setMethod($method)
                ->setParameters(self::buildRequest());
        
        
        if (array_key_exists(self::HEADER_OBJECT_ID, $headers)) {
            $obj->setOID($headers[self::HEADER_OBJECT_ID]);
        }
        
        if (array_key_exists(self::HEADER_SESSION_ID, $headers)) {
            $obj->setSID($headers[self::HEADER_SESSION_ID]);
        }
        return $obj;
    }
    
    
    public static function buildRequest() {
        
        $input = trim(file_get_contents('php://input'));
        
        
        $json = json_decode($input,true);
        
        $params = array_replace($_REQUEST, $json);
        
        return $params;
        
    }
    
    
    
    
    public function dump() {
        $arr = [];
        
        $arr = get_object_vars($this);
        
        return $arr;
    }
    
    
    public function getParm($key, $default=false) {
        if (array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }
        return $default;
    }
    
}
