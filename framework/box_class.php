<?php
/*
 * Sidebar Box class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

require_once 'form_class.php';
require_once 'page_class.php';

define("FB_SOCIAL",		"http://www.facebook.com/TandemLanguageBergamo");

class box_class
{
    private $box_data;

    public function __construct($title)
    {
        $this->box_data .= "\t\t\t<li>\n";

		if (isset($title) && $title != "")
			$this->box_data .= "\t\t\t\t<h4><span>".$title."</span></h4>\n";
    }

    public function add_content($content)
    {
        $this->box_data .= "\t\t\t\t".$content."\n";
    }

    public function get_box_data()
    {
        // close li
        $this->box_data .= "\t\t\t</li>";

        return $this->box_data;
    }
}

class login_box_class extends box_class
{
	public function __construct($title, $form_name, &$page)
	{
		// create box
		parent::__construct($title);

		// create form
		$form = new form_class($form_name, "framework/controller/form_process.php", form_class::METHOD_POST, form_class::TYPE_SIMPLE);

		// username field
		$username = new form_field("username", _("Username:"));
		$username->set_type(form_field::FIELD_TEXT);

		// password field
		$password = new form_field("password", _("Password:"));
		$password->set_type(form_field::FIELD_PASSWORD);

		// send button
		$send = new form_field("send", "", false);
		$send->set_type(form_field::FIELD_BUTTON);
		$send->set_value(_("Send"));

		//add fields
		$form->add($username);
		$form->add($password);
		$form->add($send);

		// push validator js class
		$page->AddJS("jquery.form.js");
		$page->AddJS("jquery.notify.js");

		// add validator
		$page->AddJQuery("$(\"#".$form_name."\").ajaxForm({dataType:'json', success: processReply});"); 

		// add content
		parent::add_content($form->get_form());
	}
}

class userdetails_box_class extends box_class
{
	public function __construct($title, &$page)
	{
		// create box
		parent::__construct("");

		$data = "";

		// get user data
		$user_data = $page->get_user_data();

		// user image
		if (isset($user_data['facebook']) && $user_data['facebook'] != "")
			$img_user = "<img src='http://graph.facebook.com/".$user_data['facebook']."/picture' style='border: 1px solid;' />";
		else
		{
			if ($user_data['gender'] == "M")
				$img_user = "<img src='img/user_def_male.png' style='border: 1px solid;' />";
			else
				$img_user = "<img src='img/user_def_female.png' style='border: 1px solid;' />";
		}

		// user visible
		if ($user_data['invisible'] == 0)
		{
			$img_invisible = "<img class='user_button' src='img/icons/visible.png' alt='Set Invisible' title='Set Invisible' />";
		}
		else
		{
			$img_invisible = "<img class='user_button' src='img/icons/invisible.png' alt='Set Visible' title='Set Visible' />";
		}

		$data .= "<div class='user_data'>";
		
        $data .= "<div class='user_image'>".$img_user."</div>\n";

		// user name cell
		$data .= "<div class='user_name'><span class='user_name'>".$user_data['name']." ".$user_data['surname']."</span></div>\n";

		// Button box
		$data .= "<div class='buttons_box'>\n";

		if ($user_data['admin'] == 0)
		{
			// Buddies
			$data .= "<a href=\"index.php?page=buddies\"><img class='user_button' src='img/icons/tandem.png' alt=\"Tandem\" title=\"Tandem\" /></a>\n";

			// Friends
			$data .= "<a href=\"index.php?page=friends\"><img class='user_button' src='img/icons/friends.png' alt=\"Friends\" title=\"Friends\" /></a>\n";

			// Visible/Invisible
			//$data .= "<a style=\"cursor:pointer\" onclick=\"$().tandem_toggle_visible();\">".$img_invisible."</a>\n";
			if ($user_data['invisible'] == 0)
				$data .= "<a style=\"cursor:pointer\" visible=\"1\" class=\"invisible_link\">".$img_invisible."</a>\n";
			else
				$data .= "<a style=\"cursor:pointer\" visible=\"0\" class=\"invisible_link\">".$img_invisible."</a>\n";
		}

		// settings
		$data .= "<a href=\"index.php?page=settings\"><img class='user_button' src='img/icons/settings.png' alt=\"Settings\" title=\"Settings\" /></a>\n";

		// logout
		$data .= "<a style=\"cursor:pointer\" onclick=\"$().tandem_logout();\"><img class='user_button' src='img/icons/logout.png' alt=\"Logout\" title=\"Logout\" /></a>\n";
		$data .= "</div></div>\n";

		// insert JS
		$page->AddJS("jquery.tandem.js");

		// insert jquery
		$page->AddJQuery("
		$(\"#invisible-message\").dialog({
		autoOpen: false,
		resizable: false, height: 190, width: 400,
		modal: true,
		buttons: {\"Invisible\": { text: \""._("Toggle")."\", id: \"btn_invisible\", click: function () { $().tandem_toggle_visible();} }}
		});
		$('.invisible_link').click(function(event){
		event.preventDefault();
		var a =$(this).attr(\"visible\");
		if (a == 0)
			\$(\"#visible_text\").html(\""._("You are setting yourself to be visible again!<br />Doing so users can find you in the buddies list.")."\");
		else
			\$(\"#visible_text\").html(\""._("You are setting yourself to be invisible!<br />Doing so noone can find you in the buddies list.")."\");
		\$(\"#invisible-message\").dialog(\"open\");
		return false; });
		");

		$data .= "<div id=\"invisible-message\" title=\""._("Toggle visibility")."\" style=\"display: none;\">
					<p><span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin: 0 7px 20px 0;\"></span><div id=\"visible_text\"></div></p>
					</div>";

		parent::add_content($data);
	}
}

class facebook_box_class extends box_class
{
	public function __construct($title, &$page)
	{
		parent::__construct("");

		$page->AddHead("<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = \"//connect.facebook.net/it_IT/all.js#xfbml=1\";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>");

		parent::add_content("<div class=\"fb-like-box\" data-href=\"".FB_SOCIAL."\" data-width=\"292\" data-show-faces=\"false\" data-stream=\"true\" data-header=\"false\" data-show-border=\"false\"></div>");
	}
}
?>
