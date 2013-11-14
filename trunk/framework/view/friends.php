<?php
/**
 * Friends List View File
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
$page->set_title('Friends');

// Add jquery UI
$page->AddJS("jquery-ui-1.10.1.custom.min.js");
$page->AddJS("jquery.tandem.js");
$page->AddCSS("ui-lightness/jquery-ui-1.10.1.custom.css");
$page->AddCSS("grid.css");

// get db connection
$db_conn = null;
$page->get_db($db_conn);

// Page title
$page->AddToBody("<h2 style=\"display: inline;\">"._("Friends List")."</h2>");

// trash bin
$page->AddToBody("<div id=\"trash_bin\" style=\"border: 1px dashed; float:right;\"><img src='/img/icons/trash.png' style=\"margin: 10px;\"/></div>");

$page->AddToBody("<br />"._("Click on a friend to open the profile, to remove a friend drag it on the trash bin!"));
// current user data
$current_user_data = $page->get_user_data();

// get buddies data
$data = $db_conn->user_friends_get($current_user_data['id']);

$bd_data = "<div class=\"grid grid-pad\">\n";
$cnt = 0;

foreach ($data as $friend_id => $friend_data)
{
	// get DB user data
	$user_data = $db_conn->user_get_data_by_id($friend_data['friend_id']);

	// user image
	if (isset($user_data['facebook']) && $user_data['facebook'] != "")
		$usr_img = "<img src='http://graph.facebook.com/".$user_data['facebook']."/picture' style='border: 1px solid;'/>";
	else
	{
		if ($user_data['gender'] == "M")
			$usr_img = "<img src='/img/user_def_male.png' style='border: 1px solid;'/>";
		else
			$usr_img = "<img src='/img/user_def_female.png' style='border: 1px solid;'/>";
	}

	// user real name
	$usr_name = $user_data['name']." ".$user_data['surname'];
	
	if (($cnt % 4) == 0)
		$bd_data .= "<br />";

	$bd_data .= "\t<a href=\"index.php?page=profile&id=".$user_data['id']."\"><div id=".$user_data['id']." class=\"col-1-4 draggable\" style=\"text-align: center;";
		
	if ($cnt >= 4)
		$bd_data .= "margin-top: 15px;";

	$bd_data .= "\">";

	// Buddy name
	$bd_data .= "<div style='clear: both;'><strong>".$usr_name."</strong></div><div style='margin-top: 5px;'>".$usr_img."</div></div></a>\n";

	$cnt++;
}

$bd_data .= "</div>";

// add friends list
$page->AddToBody($bd_data);

// add jquery code
$page->AddJQuery("$(\".draggable\").draggable({ revert: \"invalid\"});
	$(\"#trash_bin\").droppable({
      hoverClass: \"ui-state-active\",
      drop: function( event, ui ) {
		$(\"#delete_confirm\").data(\"user_item\", {id: $(ui.draggable).attr(\"id\")}).dialog(\"open\");
		ui.draggable.draggable('option','revert',true);
      }
    });

$(\"#delete_confirm\").dialog({
      resizable: false,
	  autoOpen: false,
      height:170,
      modal: true,
      buttons: {
        \"Remove\": function() {
		  var id = $(this).data('user_item').id;
		  $().tandem_remove_friend(id);
          $( this ).dialog(\"close\");
        },
        Cancel: function() {
          $( this ).dialog( \"close\" );
        }
      }
    });
");

$page->AddToBody("<div id=\"delete_confirm\" title=\""._("Remove friend?")."\">
  <p><span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin: 0 7px 20px 0;\"></span>"._("Do you really want to remove this friend?")."</p>
</div>");
?>
