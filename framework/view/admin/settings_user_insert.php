<?php
/**
 * Settings user insert manual View File
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

// set template file
$page->set_template('framework/templates/main.tpl');

// get user data
$user_data = $page->get_user_data();

if ($user_data['admin'] == 1)
{
	// set page title
	$page->set_title('Admin Settings');

	$page->AddToBody("<h2>"._("Manual insert user")."</h2>");

	$registration = new form_class("manual_registration", "framework/controller/form_process.php", form_class::METHOD_POST, form_class::TYPE_FIELDSET);

	// name field
	$name = new form_field("name", _("Name"));
	$name->set_type(form_field::FIELD_TEXT);
	$name->set_style("width: 250px;");

	// surname field
	$surname = new form_field("surname", _("Surname"));
	$surname->set_type(form_field::FIELD_TEXT);
	$surname->set_style("width: 250px;");

	// surname field
	$username = new form_field("username", _("Username"));
	$username->set_type(form_field::FIELD_TEXT);
	$username->set_style("width: 200px;");

	// sex field
	$sex = new form_field("sex", _("Sex"));
	$sex->set_type(form_field::FIELD_RADIO);
	$sex->set_data(array("M" => "M", "F" => "F"));

	$bdate = new form_field("birthdate", _("Birthdate"), true);
	$bdate->set_type(form_field::FIELD_TEXT);
	$bdate->set_style("width: 80px;");

	// email field
	$email = new form_field("email", _("E-Mail"));
	$email->set_type(form_field::FIELD_TEXT);
	$email->set_style("width: 250px;");

	if ($user_data['content_manager'] == 1)
	{
		// admin field
		$admin = new form_field("admin", _("Admin"));
		$admin->set_type(form_field::FIELD_CHECKBOX);
		$admin->set_value("1");
		$admin->set_style("width: 20px;");
		$admin->set_data(_("Set as admin"));

		// content manager field
		$content = new form_field("content", _("Content Mngr."));
		$content->set_type(form_field::FIELD_CHECKBOX);
		$content->set_value("1");
		$content->set_style("width: 20px;");
		$content->set_data(_("Set as content manager"));
	}

	// note field
	$note = new form_field("note", _("Note about insertion"));
	$note->set_type(form_field::FIELD_TEXTAREA);

	// send button
	$send = new form_field("send", "");
	$send->set_type(form_field::FIELD_BUTTON);
	$send->set_value(_("Send"));

	// add to form
	$registration->add($name);
	$registration->add($surname);
	$registration->add($username);
	$registration->add($sex);
	$registration->add($bdate);
	$registration->add($email);

	if ($user_data['content_manager'] == 1)
	{
		$registration->add($admin);
		$registration->add($content);
	}

	$registration->add($note);
	$registration->add($send);

	// push validator js class
	$page->AddJS("jquery.form.js");
	$page->AddJS("jquery.notify.js");
	$page->AddJS("jquery-ui-1.10.1.custom.min.js");
	$page->AddJS("jquery-dynamic-form.js");

	$page->AddCSS("ui-lightness/jquery-ui-1.10.1.custom.css");

	// add validator
	$page->AddJQuery("$(\"#manual_registration\").ajaxForm({dataType:'json', success: processReply});
					  $(\"#birthdate\").datepicker({yearRange: \"-50:+0\", changeMonth: true, changeYear: true, dateFormat: \"dd/mm/yy\"});"); 

	// add form
	$page->AddToBody($registration->get_form());
}
?>