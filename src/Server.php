<?php

namespace eduluz1976\ARPC;

/**
 * 
 * /info
 * /exec - execute an operation (run a method)
 * /get - get a class definition
 * /new - instantiate a new object. Return the OID
 * /set - set a value of an object
 * /del - 
 * get a class definition
 * 
 * Description of Server
 *
 * @author eduardoluz
 */
class Server {
   
    /**
     *
     * @var Request 
     */
    protected $request;
    
    
    public function getRequest() {
        return $this->request;
    }
    
    public function setRequest($request) {
        $this->request = $request;
        return $this;
    }
    
    public function init() {
        
        
        $this->setRequest(Request::build());
        
        if ($this->getRequest()->getSID()) {
            session_id($this->getRequest()->getSID());
        }
        
        session_start();       
        header(Request::HEADER_SESSION_ID.": ".session_id());
        
//        exit;
//        echo '<pre>';
//        print_r($this->request->dump());
//        echo '</pre>';           
        
    }
    
    
    
    public function run() {
        
        $operation = new Operation($this->getRequest()->getOperation(), 
                                    $this->getRequest(),
                                    $this);
        $operation->run();
        echo "\n\nOk...";
    }
    
    
    public static function sendJSON($envelope=[]) {
        header('Content-type: text/json');
        echo json_encode($envelope);
        exit;
    } 
    
}
