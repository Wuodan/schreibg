<?php
/**
 * Project: 
 * Contenido Content Management System
 * 
 * Description: 
 * Blank left top page
 * 
 * Requirements: 
 * @con_php_req 5.0
 * 
 *
 * @package    Contenido Backend includes
 * @version    1.0.1
 * @author     Jan Lengowski
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since contenido release <= 4.6
 * 
 * {@internal 
 *   created 2003-01-21
 *   modified 2008-06-27, Frederic Schneider, add security fix
 *
 *   $Id: include.subnav_blank.php 369 2008-06-27 14:26:40Z frederic.schneider $:
 * }}
 * 
 */

if(!defined('CON_FRAMEWORK')) {
	die('Illegal call');
}

$tpl->reset();
$tpl->generate($cfg["path"]["contenido"].$cfg["path"]["templates"] . $cfg['templates']['subnav_blank']);
?>