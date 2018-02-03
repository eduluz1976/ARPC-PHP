<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace eduluz1976\ARPC;

/**
 * Description of RemoteInstance
 *
 * @author eduardoluz
 */
class RemoteInstance {
    protected $OID;
    protected $obj;
    
    
    public function __construct($oid=false, $obj=false) {
        if ($oid) $this->setOID ($oid);
        if ($obj) $this->setObj ($obj);
    }
    
    
    public function getOID() {
        return $this->OID;
    }
    
    /**
     * 
     * @param type $oid
     * @return $this
     */
    public function setOID($oid) {
        $this->OID = $oid;
        return $this;
    }
    
    
    public function getObj() {
        return $this->obj;
    }
    
    /**
     * 
     * @param type $obj
     * @return $this
     */
    public function setObj($obj) {
        $this->obj = $obj;
        return $this;
    }

    
    
    
    
    

    public function store() {
        
        if (!array_key_exists('objs', $_SESSION)) {
            $_SESSION['objs'] = [];
        }
        
        $_SESSION['objs'][$this->OID] = serialize($this->obj);
    }
    
    
    public function load($oid=false) {
        
        if (!$oid) {
            $oid = $this->getOID();
        }
        if (array_key_exists($oid, $_SESSION['objs'])) {
        
            $this->obj = self::unserialize($_SESSION['objs'][$oid]);
        } else {
            throw \Exception('Instance not found', 1100);
        }
    }
    
    
    public static function getList() {

        $resp = [];
        
        foreach ($_SESSION['objs'] as $k => $v ) {
            $resp[$k] = base64_encode($v);
        }
        
        return $resp;             
    }
    
    
    public static function serialize($v) {
        return (serialize($v));
    }
    
    
    
    public static function unserialize($v) {
        return unserialize(($v));
    }
    
    
    public function delObj() {
        if (array_key_exists('objs', $_SESSION) && array_key_exists($this->OID, $_SESSION['objs'])) {
            unset($_SESSION['objs'][$this->OID]);
            return true;
        } else {
            return false;
        }
    }
    
    
}
