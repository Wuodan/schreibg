<?php

/**
 * Autoloader Class for Contenido
 * @version $Id: Loader.php 9886 2009-04-02 16:06:50Z dom $
 * @author Andreas Kummer, w3concepts AG
 * @copyright Copyright &copy; 2009, w3concepts AG
 */

require_once "Zend/Loader.php";

class Wdrei_Contenido_Loader extends Zend_Loader {
	
    private static $prefixes;
     
    public static function loadClass($class, $dirs = null)
    {
    	preg_match('/^([^_]*).*$/', $class, $match);
    	if (isset($match[1]) && in_array(strtolower($match[1]), self :: $prefixes)) {
    		parent :: loadClass($class, $dirs);
    	}    	
    }	
    
    public static function autoload($class)
    {
        try {
            self::loadClass($class);            
            return $class;
            
        } catch (Exception $e) {
            return false;
        }
    }   
    
	public static function registerAutoload($class = 'Wdrei_Contenido_Loader', $enabled = true, $prefixes = array('wdrei', 'zend'))
    {
    	self :: $prefixes = $prefixes;
    	parent :: registerAutoload($class, $enabled);
    }     
}

