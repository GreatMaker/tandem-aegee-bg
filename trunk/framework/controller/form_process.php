<?php
/*
 * Processes form AJAX request and dispatch data to the correct handling class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */
require_once 'form_list.php';

// form name
$form_name = $_POST['hidden_form_name'];

// require correct controller
require $form_list[$form_name]['controller'];

// get controller class
$class_name = $form_list[$form_name]['class'];

// allocate the controller
$controller = new $class_name($_POST);

// process request
$controller->process($_POST);

// get reply
$ret = $controller->get_reply();

// send reply
echo json_encode($ret);
?>
