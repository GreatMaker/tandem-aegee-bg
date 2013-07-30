<?php
/*
 * AJAX PHP script
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

require_once '../page_class.php';

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Page generator class
$page = new page_class();

// Reload false
$ret = array('reload' => 'false');

// Controllo parametro funzione
if (!isset($_POST['func']))
    $ret['error'] = _("Function not defined");

// Commutazione attivazione utente
if ($_POST['func'] == "tandem_logout")
{
	// logout
	$page->logout();

	// force reload
	$ret['reload'] = 'true';
}
else if ($_POST['func'] == "tandem_message")
{
	// get connection to DB
	 $page->get_db($db_conn);

	 // send message
	 $db_conn->message_add($_POST['from'], $_POST['to'], $_POST['message']);

	 $str_err = "";

	 if ($db_conn->GetError($str_err))
		 $ret['error'] = $str_err;
}
else
	$ret['error'] = _("Function not found");

$ret['func'] = $_POST['func'];

// Invio dati
echo json_encode($ret);
?>
