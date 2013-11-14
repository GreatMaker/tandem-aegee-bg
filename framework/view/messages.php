<?php
/**
 * Messages View File
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

// set template file
$page->set_template('framework/templates/main.tpl');

// set page title
$page->set_title('Messages');

// Add jquery UI
$page->AddJS("jquery-ui-1.10.1.custom.min.js");
$page->AddCSS("ui-lightness/jquery-ui-1.10.1.custom.css");

$page->AddToBody("<h2>"._("Messages")."</h2>");

// get db connection
$db_conn = null;
$page->get_db($db_conn);

$bd_data = $db_conn->message_get_buddies($page->get_user_id());

// contacts list
$data  = "<div style=\"overflow-y: auto; margin-bottom: 10px; margin-top: 10px; margin-left: 10px; width: 160px; height:440px; border: 1px solid grey; float: left;\"><ol id=\"usr_msg_list\">\n";

foreach ($bd_data as $id => $buddy_id)
{
	$user_data = $db_conn->user_get_data_by_id($buddy_id);

	// user image
	if (isset($user_data['facebook']) && $user_data['facebook'] != "")
		$usr_img = "<img src='http://graph.facebook.com/".$user_data['facebook']."/picture' style='border: 1px solid black;'/>";
	else
	{
		if ($user_data['gender'] == "M")
			$usr_img = "<img src='/img/user_def_male.png' style='border: 1px solid black;'/>";
		else
			$usr_img = "<img src='/img/user_def_female.png' style='border: 1px solid black;'/>";
	}

	// user real name
	$usr_name = $user_data['name']." ".$user_data['surname'];

	$data .= "<li id=\"".$user_data['id']."\" class=\"ui-widget-content\"><div style=\"text-align: center; margin-top: 10px; margin-bottom: 10px;\">\n
			<div style='clear: both;'><strong>".$usr_name."</strong></div>\n
			<div style='margin-top: 5px;'>".$usr_img."</div>\n
			</div></li>\n";
}

$data .= "</ol></div>";

// message view
$data .= "<div id=\"message_list\" style=\"margin-bottom: 10px; margin-top: 10px; margin-left: 190px; margin-right: 10px; height:440px; witdh: 100%; border: 1px solid grey; overflow-y: auto;\">"._("Select a user to show the message thread!")."</div>";

// reply div
$data .= "<div id=\"message_reply\" style=\"margin-top: 10px; margin-left: 190px; margin-right: 10px;\"><input type=\"text\" id=\"reply_msg\" style=\"border:solid 1px #BFBDBD; width: 85%;\"/><input type=\"hidden\" id=\"dest_id\" />&nbsp;<input type=\"submit\" id=\"reply_send\" style=\" width: 10%;\" value=\""._("Send")."\"/></div>";

$page->AddToBody($data);

// add jquery
$page->AddJQuery("$(\"#usr_msg_list\").bind(\"mousedown\", function (e) {e.metaKey = false;}).selectable({selected: function(event, ui){\$().tandem_load_messages(ui.selected.id);$(\"#dest_id\").val(ui.selected.id);}});
	$('#reply_send').click(function(e) {e.preventDefault(); $().tandem_message_fast($('#dest_id').val(), $('#reply_msg').val()); \$().tandem_load_messages($('#dest_id').val()); $('#reply_msg').val(\"\"); });");
?>