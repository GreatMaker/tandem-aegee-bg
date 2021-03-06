<?php
/**
 * Profile View File
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

// set template file
$page->set_template('framework/templates/main_html5.tpl');

// set page title
$page->set_title('User Profile');

// Add jquery UI
$page->AddJS("jquery-ui-1.10.1.custom.min.js");
$page->AddCSS("ui-lightness/jquery-ui-1.10.1.custom.css");

$page->AddToBody("<h2>"._("User Profile")."</h2>");

// get db connection
$db_conn = null;
$page->get_db($db_conn);

// get DB user data
$user_data = $db_conn->user_get_data_by_id($_GET['id']);

// user image
if (isset($user_data['facebook']) && $user_data['facebook'] != "")
	$usr_img = "<img src='http://graph.facebook.com/".$user_data['facebook']."/picture' />";
else
{
	if ($user_data['gender'] == "M")
		$usr_img = "<img src='/img/user_def_male.png' />";
	else
		$usr_img = "<img src='/img/user_def_female.png' />";
}

// user real name
$usr_name = $user_data['name']." ".$user_data['surname'];

$bd_data  = "";
$bd_data .= "<div style=\"clear: both; overflow:auto;\">";

// Buddy name
$bd_data .= "<div style=\"clear: both; font-size: 15px; margin-left: 10px;\"><strong>".$usr_name."</strong></div>";

// Buddy affinity
//$buddy_data = $db_conn->matching_get_single_score($page->get_user_id(), $_GET['id']);

//$affinity = ($buddy_data['score'] / $buddy_data['max_score']) * 100.0;

//$bd_data .= "<div style=\"float: right; font-size: 11px; margin-right: 10px;\">Affinity: ".round($affinity)."%</div></div>";

// Buddy image
$bd_data .= "<div style=\"border: 1px solid black; float: left; width: 50px; height: 50px; margin: 10px;\">".$usr_img."</div>";

$bd_data .= "<div style=\"overflow:auto; margin-top: 10px;\">";

// Buddy age
if ($user_data['age'] != "" && $user_data['age'] != 0)
{
	$bd_data .= "<div style=\"float: left; text-align: right; width: 20%; clear: left;\"><strong>Age:</strong></div>";
	$bd_data .= "<div style=\"float: left; margin-left: 5px; margin-bottom: 5px;\">".$user_data['age']."</div>";
}

// Buddy sex
if (isset($user_data['gender']) && $user_data['gender'] != "")
{
	$bd_data .= "<div style=\"float: left; text-align: right; width: 20%; clear: left;\"><strong>Sex:</strong></div>";
	$bd_data .= "<div style=\"float: left; margin-left: 5px; margin-bottom: 5px;\">".$user_data['gender']."</div>";
}

// Buddy about..
if (isset($user_data['about']) && $user_data['about'] != "")
{
	$bd_data .= "<div style=\"float: left; text-align: right; width: 20%; clear: left;\"><strong>About me:</strong></div>";
	$bd_data .= "<div style=\"float: left; margin-left: 5px; margin-bottom: 5px; width: 79%;\">".nl2br($user_data['about'])."</div>";
}

// Buddy languages
$user_langs	= $db_conn->user_languages_get_by_id($_GET['id']);

$langs_str_s = _("Speaks")."&nbsp;";
$lang_s_cnt  = 0;
$langs_str_l = _("Wants to learn")."&nbsp;";
$lang_l_cnt  = 0;

foreach ($user_langs as $l_id => $l_data)
{
	if ($l_data['mother_tongue'] == 1)
	{
		if ($lang_s_cnt != 0)
			$langs_str_s .= ",&nbsp;";

		$langs_str_s .= $l_data['lang_name'];
		$lang_s_cnt++;
	}
	else if ($l_data['mother_tongue'] == 0)
	{
		if ($lang_l_cnt != 0)
			$langs_str_l .= ",&nbsp;";

		$langs_str_l .= $l_data['lang_name']." ("._("now")." ".$db_conn->languages_get_level($l_data['level']).")";
		$lang_l_cnt++;
	}
}

$bd_data .= "<div style=\"float: left; text-align: right; width: 20%; clear: left;\"><strong>Languages:</strong></div>";
$bd_data .= "<div style=\"float: left; margin-left: 5px; margin-bottom: 5px; width: 79%;\">".$langs_str_s."<br />".$langs_str_l."</div>";

// Buddy common interests
$user_c_interests = $db_conn->user_interests_get_data($_GET['id']);

if (count($user_c_interests) > 0)
{
	$user_int_str  = "";
	$user_int_cnt  = 0;

	foreach ($user_c_interests as $i_id => $i_data)
	{
		if ($user_int_cnt != 0)
			$user_int_str .= ",&nbsp;";

		$user_int_str .= $i_data;
		$user_int_cnt++;
	}

	$bd_data .= "<div style=\"float: left; text-align: right; width: 20%; clear: left;\"><strong>"._("Interests").":</strong></div>";
	$bd_data .= "<div style=\"float: left; margin-left: 5px; margin-bottom: 5px; width: 79%;\">".$user_int_str."</div>";
}

$bd_data .= "</div>";
$bd_data .= "</div><br />";

if ($page->get_user_id() != $_GET['id'])
{
	// Buttons
	$bd_data .= "<div style=\"clear: both; margin-right: 10px; overflow:auto;\">";

	if ($db_conn->user_friends_is_friend($page->get_user_id(), $_GET['id']) == false)
		$bd_data .= "<a style=\"cursor: pointer;\" onclick=\"$().tandem_add_friend(".$_GET['id'].")\"><div style=\"float: right; border: 1px solid black; padding: 5px; margin-left: 10px;\"><img style=\"vertical-align: middle;\" src=\"/img/icons/starred.png\" /><span style=\"display:inline-block; vertical-align:middle; line-height:30px; \">&nbsp;"._("Add to favourites")."</span></div></a>";

	if ($db_conn->user_block_is_blocked($page->get_user_id(), $_GET['id']) == false)
		$bd_data .= "<a class=\"block_link\" block_id=\"".$_GET['id']."\" style=\"cursor: pointer;\"><div style=\"float: right; border: 1px solid black; padding: 5px; margin-left: 10px;\"><img style=\"vertical-align: middle;\" src=\"/img/icons/lock.png\" /><span style=\"display:inline-block; vertical-align:middle; line-height:30px; \">&nbsp;"._("Block user")."</span></div></a>";
	else
		$bd_data .= "<a style=\"cursor: pointer;\" onclick=\"$().tandem_unblock_user(".$_GET['id'].")\"><div style=\"float: right; border: 1px solid black; padding: 5px; margin-left: 10px;\"><img style=\"vertical-align: middle;\" src=\"/img/icons/unlock.png\" /><span style=\"display:inline-block; vertical-align:middle; line-height:30px; \">&nbsp;"._("Unblock user")."</span></div></a>";

	$bd_data .= "<a class=\"dialog_link\" from=\"".$page->get_user_id()."\" to=\"".$_GET['id']."\" style=\"cursor: pointer;\"><div style=\"float: right; border: 1px solid black; padding: 5px; margin-left: 10px;\"><img style=\"vertical-align: middle;\" src=\"/img/icons/message.png\" /><span style=\"display:inline-block; vertical-align:middle; line-height:30px; \">&nbsp;"._("Send message")."</span></div></a>";
	$bd_data .= "</div>";

	// send message dialog
	$page->AddJQuery("
		message_field_html = $('#dialog-message').html();
		$(\"#dialog-message\").dialog({
			autoOpen: false,
			resizable: false, width: 520, height: 320,
			modal: true,
			buttons: {\"Send\": { text: \""._("Send")."\", id: \"btn_send\", click: function () { $().tandem_message($(this).data('msg_data').msg_from, $(this).data('msg_data').msg_to, $('#message_field').val());} }},
			close: function() {if($('#form_message').length) {\$(this).find('form')[0].reset();} $('#btn_send').show();}
		});
		$(\"#block-message\").dialog({
			autoOpen: false,
			resizable: false, height: 170,
			modal: true,
			buttons: {\"Block\": { text: \""._("Block")."\", id: \"btn_block\", click: function () { $().tandem_block_user($(this).data('msg_data').block);} }}
		});
		$('.dialog_link').click(function(event){
			event.preventDefault();
			\$(\"#dialog-message\").html(message_field_html);
			var from =$(this).attr(\"from\");
			var to =$(this).attr(\"to\");
			\$(\"#dialog-message\").data(\"msg_data\", {msg_from: from, msg_to: to}).dialog(\"open\");
			return false; });

		$('.block_link').click(function(event){
			event.preventDefault();
			var block_id =$(this).attr(\"block_id\");
			\$(\"#block-message\").data(\"msg_data\", {block: block_id}).dialog(\"open\");
			return false; });
	");
}

// set page
$page->AddToBody("<br />");
$page->AddToBody($bd_data);

// Message div
$div_send_message = "<div id=\"dialog-message\" title=\""._("Send Message")."\" style=\"display: none;\">
					<p><form id =\"form_message\">
						<textarea name=\"message_field\" id=\"message_field\" rows=\"8\" cols=\"62\" class=\"ui-widget-content ui-corner-all\" style=\"resize: none;\"></textarea>
					</form></p>
					</div>";

// block div
$div_send_block = "<div id=\"block-message\" title=\""._("Block user")."\" style=\"display: none;\">
					<p><span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin: 0 7px 20px 0;\"></span>"._("Do you really want to block this user?")."</p>
					</div>";

$page->AddToBody($div_send_message);
$page->AddToBody($div_send_block);
?>
