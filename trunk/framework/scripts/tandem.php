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
require_once '../mail_class.php';

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

	// force redirect to homepage
	$ret['redirect'] = 'index.php?page=home';
}
else if ($_POST['func'] == "tandem_invisible")
{
	// get connection to DB
	$page->get_db($db_conn);

	// get user data
	$user_data = $page->get_user_data();

	// set invisible flag
	$db_conn->user_toggle_invisible($user_data['id']);

	// force reload
	$ret['reload'] = 'true';
}
else if ($_POST['func'] == "tandem_message")
{
	// get connection to DB
	$page->get_db($db_conn);

	 $user_from_data = $db_conn->user_get_data_by_id($_POST['from']);
	 $user_to_data = $db_conn->user_get_data_by_id($_POST['to']);

	 // check user block
	 if ($db_conn->user_block_is_blocked($_POST['to'], $_POST['from']) == true)
	 {
		 $ret['error'] = _("You can't message this user, you've been blocked!");
	 }
	 else
	 {
		// send message
		$mail = new mailman_class();

		$mail->set_sender($user_from_data['name']);
		$mail->set_sender_id($user_from_data['id']);
		$mail->set_receiver($user_to_data['name']);
		$mail->set_receiver_mail($user_to_data['email']);
		$mail->set_object(_("New message from the Tandem Project Bergamo!"));
		$mail->set_user_message($_POST['message']);
		$mail->set_user_image($user_from_data['facebook']);

		// add do DB
		$db_conn->message_add($_POST['from'], $_POST['to'], $_POST['message']);

		$str_err = "";

		if ($db_conn->GetError($str_err))
			$ret['error'] = $str_err;
		else
		{
		   // send message
		   $mail->send_message();
		}

		$ret['success'] = _("Message sent correctly");
	 }
}
else if ($_POST['func'] == "tandem_add_friend")
{
	// get connection to DB
	$page->get_db($db_conn);

	// add friend
	$db_conn->user_friends_add($page->get_user_id(), $_POST['friend']);
	
	$str_err = "";

	if ($db_conn->GetError($str_err))
		$ret['error'] = $str_err;
	else
		$ret['redirect'] = 'index.php?page=friends';
}
else if ($_POST['func'] == "tandem_remove_friend")
{
	// get connection to DB
	$page->get_db($db_conn);

	// add friend
	$db_conn->user_friends_remove($page->get_user_id(), $_POST['friend']);
	
	$str_err = "";

	if ($db_conn->GetError($str_err))
		$ret['error'] = $str_err;
	else
		// force reload
		$ret['reload'] = 'true';
}
else if ($_POST['func'] == "tandem_block_user")
{
	// get connection to DB
	$page->get_db($db_conn);

	// add friend
	$db_conn->user_block_add($page->get_user_id(), $_POST['block']);

	$str_err = "";

	if ($db_conn->GetError($str_err))
		$ret['error'] = $str_err;
	else
		// force reload
		$ret['reload'] = 'true';
}
else if ($_POST['func'] == "tandem_unblock_user")
{
	// get connection to DB
	$page->get_db($db_conn);

	// add friend
	$db_conn->user_block_remove($page->get_user_id(), $_POST['block']);
	
	$str_err = "";

	if ($db_conn->GetError($str_err))
		$ret['error'] = $str_err;
	else
		// force reload
		$ret['reload'] = 'true';
}
else
	$ret['error'] = _("Function not found");

$ret['func'] = $_POST['func'];

// Invio dati
echo json_encode($ret);
?>
