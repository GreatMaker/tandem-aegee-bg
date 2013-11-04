<?php
/**
 * Main View File
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
$page->set_title('Home');

// Add jquery UI
$page->AddJS("jquery-ui-1.10.1.custom.min.js");
$page->AddJS("jquery.tandem.js");
$page->AddCSS("ui-lightness/jquery-ui-1.10.1.custom.css");

// Add contents from database
$page->add_db_contents($page_req);

// Force message dialog
if ((isset($_GET["option"]) && $_GET["option"] == "message") && ($_GET["to"] != ""))
{
	// current user data
	$current_user_data = $page->get_user_data();

	$page->AddJQuery("
			$(\"#dialog-message\").dialog({
				autoOpen: true,
				resizable: false, width: 520, height: 320,
				modal: true,
				buttons: {\"Send\": { text: \""._("Send")."\", id: \"btn_send\", click: function () { $().tandem_message(".$current_user_data['id'].", ".$_GET["to"].", $('#message_field').val());} }},
				close: function() {if($('#form_message').length) {\$(this).find('form')[0].reset();} $('#btn_send').show();}
			});
		");
	
	// Message div
	$div_send_message = "<div id=\"dialog-message\" title=\""._("Send Message")."\" style=\"display: none;\">
						<p><form id =\"form_message\">
							<textarea name=\"message_field\" id=\"message_field\" rows=\"8\" cols=\"62\" class=\"ui-widget-content ui-corner-all\" style=\"resize: none;\"></textarea>
						</form></p>
						</div>";

	$page->AddToBody($div_send_message);
}
?>
