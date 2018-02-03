<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace eduluz1976\ARPC;

/**
 * Description of Operation
 *
 * @author eduardoluz
 */
class Operation  {

    
    const OP_GET = 'get';
    const OP_SET = 'set';
    const OP_NEW = 'new';
    const OP_DEL = 'del';
    const OP_INFO = 'info';
    const OP_EXEC = 'exec';
    
    protected $op;
    
    /**
     *
     * @var Request 
     */
    protected $request;
    protected $server;
    
    public function __construct($op, $request, &$server) {
        $this->op = $op;
        $this->request = $request;
        $this->server = $server;
    }
    
    public function run() {
        
        switch ($this->op) {
            case Operation::OP_NEW: return $this->runNewClassInstance(); 
            case Operation::OP_INFO: return $this->runGetInfo(); 
            case Operation::OP_GET: return $this->runGetClassInstance(); 
            case Operation::OP_SET: return $this->runSetClassInstance(); 
            case Operation::OP_DEL: return $this->runDelClassInstance(); 
        }
        
    }
    
    
    public function runNewClassInstance() {
       
        $className = str_replace('/', '\\', $this->request->getClassName().'/'.$this->request->getMethod());
        

//        echo '<pre>';
//        print_r($this->request->dump());
//        echo '</pre>';           
        
        $parms = array_values($this->request->getParameters());
        
        
        switch (count($parms)) {
            case 0 : $obj = new $className(); break;
            case 1 : $obj = new $className($parms[0]); break;
            case 2 : $obj = new $className($parms[0],$parms[1]); break;
            case 3 : $obj = new $className($parms[0],$parms[1],$parms[2]); break;
            case 4 : $obj = new $className($parms[0],$parms[1],$parms[2],$parms[3]); break;
            case 5 : $obj = new $className($parms[0],$parms[1],$parms[2],$parms[3],$parms[4]); break;
        }
        
        $oid = md5(time().session_id());
        
        $instance = new RemoteInstance();
        $instance->setOID($oid)
                ->setObj($obj);
        
        $instance->store();
        
        header(Request::HEADER_OBJECT_ID.": ".$oid);
        
        Server::sendJSON($obj);
        
    }
    
    
    public function runGetInfo(){
        
        Server::sendJSON(RemoteInstance::getList());
           
    }
    
    public function runGetClassInstance() {
        $instance = new RemoteInstance($this->request->getOID());
        $instance->load();
        
        $result = [
            'obj' =>  base64_encode(RemoteInstance::serialize($instance->getObj()))
        ];
        
        Server::sendJSON($result);
    }
    
    public function runSetClassInstance() {
        $instance = new RemoteInstance($this->request->getOID());
        $instance->load();
        
        $obj = RemoteInstance::unserialize(
                base64_decode(
                $this->request->getParm('obj'))
                );
        

        $instance->setObj($obj);
        
        $instance->store();
        
        $result = [
            'obj' => base64_encode(RemoteInstance::serialize($obj))
        ];
        
        Server::sendJSON($result);
    }
    
    
    public function runDelClassInstance() {
        $instance = new RemoteInstance($this->request->getOID());
        $instance->delObj();
        
        $result = ['success'=>true];
        
        Server::sendJSON($result);
    }
    
    
    
}
