<?php
/*
 * Sidebar Box class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

require_once 'form_class.php';
require_once 'page_class.php';
require_once 'table_class.php';

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
		$send->set_value(_("Invia"));

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

		// get user data
		$user_data = $page->get_user_data();

		// user table
		$tbl = new HTML_Table(null, '', 0, 0, 0);
		$tbl->addRow();

		// user image cell
        $tbl->addCell("img");

		// user name cell
        $tbl->addCell("name");

		parent::add_content($tbl->display());

		// http://graph.facebook.com/sarfraz.anees/picture
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

		parent::add_content("<div class=\"fb-like-box\" data-href=\"http://www.facebook.com/TandemLanguageBergamo\" data-width=\"292\" data-show-faces=\"true\" data-stream=\"true\" data-header=\"false\"></div>");
		/*
		
		 <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/it_IT/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
		 */
	}
}
?>
