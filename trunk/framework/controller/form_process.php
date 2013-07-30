<?php
/*
 * Processes form AJAX request and dispatch data to the correct handling class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */
require_once 'form_list.php';

$ret = array();

// form name
$form_name = $_POST['hidden_form_name'];

// require correct controller
require_once $form_list[$form_name]['controller'];

// get controller class
$class_name = $form_list[$form_name]['class'];

// check class existance
if (!class_exists($class_name))
{
	$ret['error'] = _("Error allocating controller");

	// send reply
	die(json_encode($ret));
}

try
{
	// allocate the controller
	$controller = new $class_name($_POST);
	//$objInstance = new ReflectionClass($class_name);
	//$controller = $objInstance->newInstanceArgs($_POST);

	// process request
	$controller->process();

	// get reply
	$ret = $controller->get_reply();
}
catch (Exception $e)
{

}

// send reply
echo json_encode($ret);
?>
