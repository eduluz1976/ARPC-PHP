<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace eduluz1976\ARPC;

/**
 * Description of Utils
 *
 * @author eduardoluz
 */
class Utils {
    
    
    
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
