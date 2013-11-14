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
else if ($_POST['func'] == "tandem_message_fast")
{
	// get connection to DB
	$page->get_db($db_conn);

	$user_from_data = $db_conn->user_get_data_by_id($page->get_user_id());
	$user_to_data = $db_conn->user_get_data_by_id($_POST['to']);

	if (!isset($_POST['to']) || $_POST['to'] == "")
	{
		$ret['error'] = _("Please select a user first!");
	}
	else
	{
		// check user block
		if ($db_conn->user_block_is_blocked($_POST['to'], $page->get_user_id()) == true)
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
		   $db_conn->message_add($page->get_user_id(), $_POST['to'], $_POST['message']);

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
else if ($_POST['func'] == "tandem_load_messages")
{
	// get connection to DB
	$page->get_db($db_conn);

	$msg_data = $db_conn->message_get_thread($page->get_user_id(), $_POST['user_id']);

	foreach ($msg_data as $id => $m_data)
	{
		// user image
		if (isset($m_data['facebook']) && $m_data['facebook'] != "")
			$usr_img = "<img src='http://graph.facebook.com/".$m_data['facebook']."/picture?width=40&height=40' />";
		else
		{
			if ($m_data['gender'] == "M")
				$usr_img = "<img src='/img/user_def_male.png' height=\"40\" width=\"40\" />";
			else
				$usr_img = "<img src='/img/user_def_female.png' height=\"40\" width=\"40\" />";
		}

		// timestamp
		$timestamp = "<span style=\"color: grey; font-size: 7pt;\">".$m_data['timestamp']."</span>";

		if ($m_data['id'] == $page->get_user_id())
		{
			$ret['list'] .= "<div style=\"margin: 10px; min-height:60px; height:auto !important;height:60px; clear:right;\">
			<div style=\"float: left;\">".$usr_img."</div>
			<div style=\"display: block; margin-left: 48px;\"><strong>".$m_data['name']."&nbsp;".$m_data['surname']."</strong></div>
			<div style=\"display: block; margin-left: 48px;\">".nl2br($m_data['message'])."<br />".$timestamp."</div>
			</div>";
		}
		else
		{
			$ret['list'] .= "<div style=\"margin: 10px; min-height:60px; height:auto !important;height:60px; clear:right;\">
			<div style=\"float: right; \">".$usr_img."</div>
			<div style=\"display: block; margin-right: 48px; text-align: right;\"><strong>".$m_data['name']."&nbsp;".$m_data['surname']."</strong></div>
			<div style=\"display: block; margin-right: 48px; text-align: right;\">".nl2br($m_data['message'])."<br />".$timestamp."</div>
			</div>";
		}
	}
}
else
	$ret['error'] = _("Function not found");

$ret['func'] = $_POST['func'];

// Invio dati
echo json_encode($ret);
?>
