<?php
/**
 * Project: 
 * Contenido Content Management System
 * 
 * Description: 
 * Contenido Misc Configurations
 * 
 * Requirements: 
 * @con_php_req 5.0
 * 
 *
 * @package    Contenido Backend includes
 * @version    1.4.8
 * @author     Holger Librenz
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since contenido release <= 4.6
 * 
 * {@internal 
 *   created 2004-02-24
 *   modified 2008-06-25, Frederic Schneider, add security fix
 *   modified 2008-07-04, Dominik Ziegler, fixed bug CON-174
 *   modified 2008-11-10 Rudi Bieller Commented out display_errors as this should be handled as defined in php.ini by default
 *   modified 2008-11-18, Murat Purc, add UrlBuilder configuration
 *   modified 2008-12-04, Bilal Arslan, added for config-password examples.
 *
 *   $Id: config.misc.php 1007 2009-04-09 13:30:25Z timo.trautmann $:
 * }}
 * 
 */

if(!defined('CON_FRAMEWORK')) {
	die('Illegal call');
}

global $cfg;

/* IMPORTANT! Put your modifications into the file "config.local.php"
   to prevent that your changes are overwritten during a system update. */

/* Misc settings
 * ----------------------------------
 *
 * Actually no variables, but important settings
 * for error handling and logging.
 */

/* Current Contenido Version. You shouldn't change this
   value unless you know what you are doing. */
$cfg['version'] = '4.8.12';

/* CVS Date tag */
$cfg['datetag'] = '$Date: 2007/07/20 22:18:31 $';

/* Backend timeout */
$cfg["backend"]["timeout"] = 60;

/* Use Pseudo-Cron? */
$cfg["use_pseudocron"] = true;

/* If you want to measure function timing set this to true */
$cfg["debug"]["functiontiming"] = false;

/* If you want to measure backend page rendering times, set this
   to true */

$cfg["debug"]["rendering"] = false;

/* To output the code when editing and browsing the frontend, set
   this to true */
$cfg["debug"]["codeoutput"] = false;

/* If true, use the field "urlname" for resolving. "name" otherwise */
$cfg["urlpathresolve"] = false;

/* E-Mail-Address where bug reports will be sent to */
$cfg['bugreport']['targetemail'] = 'bugreport@contenido.de';

/* The available charsets */
$cfg['AvailableCharsets'] = array(
    'iso-8859-1',
    'iso-8859-2',
    'iso-8859-3',
    'iso-8859-4',
    'iso-8859-5',
    'iso-8859-6',
    'iso-8859-7',
    'iso-8859-8',
    'iso-8859-8-i',
    'iso-8859-9',
    'iso-8859-10',
    'iso-8859-11',
    'iso-8859-12',
    'iso-8859-13',
    'iso-8859-14',
    'iso-8859-15',
	'iso-8859-16',
    'windows-1250',
    'windows-1251',
    'windows-1252',
    'windows-1253',
    'windows-1254',
    'windows-1255',
    'windows-1256',
    'windows-1257',
    'windows-1258',
    'koi8-r',
    'big5',
    'gb2312',
    'utf-8',
    'utf-7',
    'x-user-defined',
    'euc-jp',
    'ks_c_5601-1987',
    'tis-620',
    'SHIFT_JIS'
);


/* Error handling settings
 * ----------------------------------
 *
 * Actually no variables, but important settings
 * for error handling and logging.
 */

/* Don't display errors */
//@ini_set("display_errors",true);

/* Log errors to a file */
@ini_set("log_errors",true);

/* The file in which we write the error log */
@ini_set("error_log",$cfg["path"]["contenido"]."logs/errorlog.txt");

/* Report all errors except warnings */
error_reporting (E_ALL ^E_NOTICE);


/* Session data storage container (PHPLIB)
 * ----------------------------------
 *
 * Different session data storage containers are available.
 * file	= session data will be stored in a file on the file system
 * sql	= session data will be stored in a database table - as it is
 */

/* default container is sql */
$cfg["session_container"] = 'sql';

/* Use heap table to accelerate statitics (off by default) */
$cfg["statistics_heap_table"] = false;

/* HTTP parameter check
 *
 * This feature checks GET and POST parameters against a whitelist defined in
 * $cfg['http_params_check']['config']. Depending on mode administrated in the
 * same config as the whitelist contenido will stop processing in case of unknown
 * or invalid GET parameter.
 *
 * For further informations and initial discussion see  http://contenido.org/forum/viewtopic.php?p=113492!
 *
 * Special thx to kummer!
 */
// turns parameter checking on or off
$cfg['http_params_check']['enabled'] = false;

// configuration file (whitelist and mode)
$cfg['http_params_check']['config'] = $cfg["path"]["contenido"] . $cfg["path"]["includes"] . '/config.http_check.php';

/* max file size for one session file */
$cfg['session_line_length'] = 99999;

/* Cache settings
 * ----------------------------------
 */
$cfg["cache"]["disable"] = true;
$cfg["cache"]["dir"]	 = "cache/";
$cfg["cache"]["lifetime"]= 3600;

/* GenericDB driver */
$cfg['sql']['gdb_driver'] = 'mysql';

/* Help system, currently not used */
$cfg['help'] = false;

/* Configure page if Contenido is unable to run (e.g. no database connection)
 * It is wise to create a maintenance HTML page for redirection, so you won't
 * confuse your customers.
 *
 * Note: The URL should be absolute with http:// in front of it.
 */
$cfg["contenido"]["errorpage"] = "";

/* Configure an email address to alert when Contenido is unable to run. */
$cfg["contenido"]["notifyonerror"] = "";

/* Configure how often the notification email is sent, in minutes */
$cfg["contenido"]["notifyinterval"] = 20;


/**
 * UrlBuilder settings
 * -------------------
 * 'name'   =  The name of UrlBuilder to use. 
 * 'config' = Default UrlBuilder configuration, depends on used UrlBuilder
 *
 * Defined name is used by /contenido/classes/UrlBuilder/Contenido_UrlBuilderFactory.class.php
 */
$cfg['url_builder']['name']   = 'front_content';
$cfg['url_builder']['config'] = array();
/*
// Example setting for UrlBuilder 'custom_path':
$cfg['url_builder']['name']   = 'custom_path';
$cfg['url_builder']['config'] = array('prefix' => 'rocknroll', 'suffix' => '.4fb', 'separator' => ',');
*/

/**
 * Password Settings
 * For more comments please look in class.conuser.php file
 */
// Enable or disable checking password (true or false)
$cfg['password']['check_password_mask'] = false;

// Minimum length of password (num characters). Default is 8.
$cfg['password']['min_length'] = 6;

// If set to a value greater than 0 so many lower and upper case character must appear in the password.
// (e.g.: if set to 2, 2 upper and 2 lower case characters must appear)
$cfg['password']['mixed_case_mandatory'] = 3;

// If 'symbols_mandatory' set to a value greater than 0, at least so many symbols has to appear in given password.
$cfg['password']['symbols_mandatory'] = 3;

// If set to a value greater than 0, at least $cfg['password']['numbers_mandatory'] numbers must be in password
$cfg['password']['numbers_mandatory'] = 3;


?>