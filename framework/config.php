<?php
/*
 * Framework Config file
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

require_once 'model/db_config.php';

// Version
define("MAJOR_VER", 0);
define("MINOR_VER", 0);
define("SUFFIX_VER", "alfa");

// Page title
define("PAGE_TITLE_PREFIX", "Tandem Project Bergamo");

// LDAP Login
define("LDAP_LOGIN_SERVER", "193.204.255.31");
define("LDAP_ENABLE",		true);
?>
