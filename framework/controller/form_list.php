<?php
/*
 * Form list File
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

$form_list = array();

$form_list["login"]					= array("controller" => "login_ctrl.php", "class" => "login_ctrl");
$form_list["registration"]			= array("controller" => "register_ctrl.php", "class" => "register_ctrl");
$form_list["manual_registration"]	= array("controller" => "manual_register_ctrl.php", "class" => "manual_register_ctrl");
$form_list["settings"]				= array("controller" => "settings_ctrl.php", "class" => "settings_ctrl");
$form_list["erasmus_buddy"]			= array("controller" => "erasmus_buddy_ctrl.php", "class" => "erasmus_buddy_ctrl");
?>
