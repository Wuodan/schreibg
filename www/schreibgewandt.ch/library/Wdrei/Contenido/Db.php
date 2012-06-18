<?php


/**
 * Collection of static methods for Contenido database access.
 * @version $Id: Db.php 9890 2009-04-02 18:05:19Z dom $
 * @author Andreas Kummer, w3concepts AG
 * @copyright Copyright &copy; 2009, w3concepts AG
 */

class Wdrei_Contenido_Db {

	private function __construct() {
	}
	
	public static function tblprefix($query) {
		
		$prefix = Zend_Registry :: getInstance()->config->database->params->tblprefix;
		
		if ($prefix == null || $prefix == 'con_') {
			return $query;
		}
		
		return preg_replace('/([^a-zA-Z\\.]|^)con_/', "$1$prefix", $query);
	}
}