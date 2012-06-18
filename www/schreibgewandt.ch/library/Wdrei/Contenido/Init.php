<?php

/**
 * Init Class for front_content.php
 * @version $Id: Init.php 9965 2009-04-07 17:15:38Z dom $
 * @author Andreas Kummer, w3concepts AG
 * @copyright Copyright &copy; 2009, w3concepts AG
 */

set_include_path(realpath(dirname(__FILE__) . '/../..') . PATH_SEPARATOR . get_include_path());
require_once "Wdrei/Contenido/Loader.php";

class Wdrei_Init {

	private $saveOutput = false;
	private $out;
	private $startTime;

	private function __construct($environment) {

		Wdrei_Contenido_Loader :: registerAutoload();

		Zend_Registry :: getInstance()->config = new Zend_Config_Ini('Wdrei/Contenido/config.ini', $environment);
		
		/*
		 * Initialisierung der Datenbankverbindung
		 */
		Zend_Registry :: getInstance()->db = Zend_Db :: factory(Zend_Registry :: getInstance()->config->database);
	}

	public static function getInstance($environment = 'standard') {

		static $instance;

		if (!isset ($instance)) {
			$instance = new self($environment);
		}

		return $instance;
	}
	
	private function resizeImage() {
		
		Wdrei_Image_Resize :: getInstance()->setDimensions($_GET['w'], $_GET['h'], $_GET['b'])->setImagePath($_GET['p'])->outputImage();
		exit(0);
	}

	public function start() {

		$this->startTime = microtime(true);
		
		if (isset($_GET['imageresize'])) {
			$this->resizeImage();
		}
		
		if (isset($_GET['restricted'])) {
			$this->getRestrictedRessource();
		}

		/*
		 * Einschalten der Ausgabepufferung
		 */
		ob_start();

		/*
		 * Speicherung Benutzername und Passwort für phpBB-Autologin
		 */
		if (isset ($_REQUEST['username']) && isset ($_REQUEST['password'])) {
			$session = new Zend_Session_Namespace('wdrei');
			$session->username = $_REQUEST['username'];
			$session->password = $_REQUEST['password'];

		} else
			if (isset ($_REQUEST['logout']) && $_REQUEST['logout'] == true) {
				$session = new Zend_Session_Namespace('wdrei');
				$session->unsetAll();
			}

		if (Zend_Registry :: getInstance()->config->cache->page->enable) {
			// Caching
			$out = Wdrei_Contenido_Cache_Page :: getInstance($_REQUEST, array (
				$session->username,
				$session->password
			))->getOut();
			if (!empty ($out)) {
				ob_end_clean();
				echo $out;
				exit ();
			}
		}

		if (Zend_Registry :: getInstance()->config->rewrite->enable) {
			$obj = call_user_func(array (
			Zend_Registry :: getInstance()->config->rewrite->factory, 'getInstance'));
			$content = $obj->setIdsInGlobalScope();
		}
	}

	public function finish() {

		$resultTime = (microtime(true) - $this->startTime) * 1000;

		/*
		 * Ausschalten der Ausgabepufferung
		 */
		$content = ob_get_contents();
		if (Zend_Registry :: getInstance()->config->rewrite->enable) {
			$obj = call_user_func(array (
			Zend_Registry :: getInstance()->config->rewrite->factory, 'getInstance'));
			$content = $obj->getContent($content);
		}

		if (Zend_Registry :: getInstance()->config->transformation != null) {
			foreach (Zend_Registry :: getInstance()->config->transformation->toArray() as $transformation => $enabled) {
				if ($enabled) {
					$obj = call_user_func(array ($transformation, 'getInstance'));
					$content = $obj->getContent($content);
				}
			}
		}
		
		if (Zend_Registry :: getInstance()->config->cache->page->enable) {
			// Caching
			Wdrei_Contenido_Cache_Page :: getInstance()->save($content);
		}
		
		ob_end_clean();
		echo $content;
	}

	private function getRestrictedRessource() {
		
		$session = new Zend_Session_Namespace('wdrei');
		Wdrei_Contenido_AccessControl_Ressource :: getInstance($_GET['p'], $_GET['g'], $session->username, $session->password)->getRessource();
	}
}
