<?php

/******************************************
* File      :   config.php
* Project   :   Contenido
* Descr     :   Defines all general
*               variables of Contenido.
*
* � four for business AG
******************************************/

global $cfg;

/* Section 1: Path settings
 * ------------------------
 *
 * Path settings which will vary along different
 * Contenido settings.
 *
 * A little note about web and server path settings:
 * - A Web Path can be imagined as web addresses. Example:
 *   http://192.168.1.1/test/
 * - A Server Path is the path on the server's hard disk. Example:
 *   /var/www/html/contenido    for Unix systems OR
 *   c:/htdocs/contenido        for Windows systems
 *
 * Note: If you want to modify the locations of subdirectories for
 *       some reason (e.g. the includes directory), see Section 8.
 */

/* The root server path to the contenido backend */
$cfg['path']['contenido']               = '/home/schreibg/www/schreibgewandt.ch/contenido/';

/* The web server path to the contenido backend */
$cfg['path']['contenido_fullhtml']      = 'http://schreibgewandt.ch/contenido/';

/* The root server path where all frontends reside */
$cfg['path']['frontend']                = '/home/schreibg/www/schreibgewandt.ch';

/* The root server path to the conlib directory */
$cfg['path']['phplib']                  = '/home/schreibg/www/schreibgewandt.ch/conlib/';

/* The root server path to the pear directory */
$cfg['path']['pear']                    = '/home/schreibg/www/schreibgewandt.ch/pear/';

/* The server path to the desired WYSIWYG-Editor */
$cfg['path']['wysiwyg']                 = '/home/schreibg/www/schreibgewandt.ch/contenido/external/wysiwyg/tinymce3/';

/* The web path to the desired WYSIWYG-Editor */
$cfg['path']['wysiwyg_html']            = 'http://schreibgewandt.ch/contenido/external/wysiwyg/tinymce3/';

/* The server path to all WYSIWYG-Editors */
$cfg['path']['all_wysiwyg']                 = '/home/schreibg/www/schreibgewandt.ch/contenido/external/wysiwyg/';

/* The web path to all WYSIWYG-Editors */
$cfg['path']['all_wysiwyg_html']            = 'http://schreibgewandt.ch/contenido/external/wysiwyg/';





/* Section 2: Database settings
 * ----------------------------
 *
 * Database settings for MySQL. Note that we don't support
 * other databases in this release.
 */

/* The prefix for all contenido system tables, usually "con" */
$cfg['sql']['sqlprefix'] = 'con';

/* The host where your database runs on */
$contenido_host = 'schreibg.mysql.db.internal';

/* The database name which you use */
$contenido_database = 'schreibg_contenido';

/* The username to access the database */
$contenido_user = 'schreibg_umvkm';

/* The password to access the database */
$contenido_password = 'PtGhBw4SYu';

$cfg["database_extension"] = 'mysql';

$cfg["nolock"] = 'false';

$cfg["is_start_compatible"] = false;


/* Security fix */
if ( isset($_REQUEST['cfg']) ) { exit; }
if ( isset($_REQUEST['cfgClient']) ) { exit; }
?>
