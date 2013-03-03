<?php
/*
 * Processes form AJAX request and dispatch data to the correct handling class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
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
	$ret['error'] = _("Errore allocazione controller");

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
