<?php
/*
 * Sidebar Box class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

require_once 'form_class.php';
require_once 'page_class.php';
require_once 'table_class.php';

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
			$img_link = "<img src='http://graph.facebook.com/".$user_data['facebook']."/picture' />";
		else
		{
			if ($user_data['gender'] == "M")
				$img_link = "<img src='img/user_def_male.png' />";
			else
				$img_link = "<img src='img/user_def_female.png' />";
		}

		$data .= "<div class='user_data'>";
		
        $data .= "<div class='user_image'>".$img_link."</div>\n";

		// user name cell
		$data .= "<div class='user_name'><span class='user_name'>".$user_data['name']." ".$user_data['surname']."</span></div>\n";

		// tandem
		$data .= "<div class='buttons_box'>\n";
		$data .= "<img class='user_button' src='img/icons/tandem.png' alt=\"Tandem\" title=\"Tandem\" />\n";

		// settings
		$data .= "<img class='user_button' src='img/icons/settings.png' alt=\"Settings\" title=\"Settings\" />\n";

		// logout
		$data .= "<a style=\"cursor:pointer\" onclick=\"$().tandem_logout();\"><img class='user_button' src='img/icons/logout.png' alt=\"Logout\" title=\"Logout\" /></a>\n";
		$data .= "</div></div>\n";

		// insert JS
		$page->AddJS("jquery.tandem.js");

		parent::add_content($data);
	}
}

class facebook_box_class extends box_class
{
	public function __construct($title, &$page)
	{
		parent::__construct($title);

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
