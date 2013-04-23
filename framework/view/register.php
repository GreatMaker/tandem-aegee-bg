<?php
/**
 * Register View File
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

// set template file
$page->set_template('framework/templates/main.tpl');

// set page title
$page->set_title('Register');

$page->AddToBody("<h2>"._("Finish registration")."</h2>");

$registration = new form_class("registration", "framework/controller/form_process.php", form_class::METHOD_POST, form_class::TYPE_FIELDSET);

// name field
$name = new form_field("name", _("Name"), true);
$name->set_type(form_field::FIELD_TEXT);
$name->set_value($_SESSION['name']);
$name->set_style("width: 250px;");

// surname field
$surname = new form_field("surname", _("Surname"), true);
$surname->set_type(form_field::FIELD_TEXT);
$surname->set_value($_SESSION['surname']);
$surname->set_style("width: 250px;");

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

// facebook field
$fb = new form_field("fb", _("Facebook"));
$fb->set_type(form_field::FIELD_TEXT);
$fb->set_style("width: 350px;");

// About you field
$about = new form_field("about", _("About you"));
$about->set_type(form_field::FIELD_TEXTAREA);

// send button
$send = new form_field("send", "");
$send->set_type(form_field::FIELD_BUTTON);
$send->set_value(_("Send"));

// hidden username field
$id = new form_field("id", "");
$id->set_type(form_field::FIELD_HIDDEN);
$id->set_value($_SESSION['id']);

// hidden username field
$username = new form_field("username", "");
$username->set_type(form_field::FIELD_HIDDEN);
$username->set_value($_SESSION['username']);

// add to form
$registration->add($name);
$registration->add($surname);
$registration->add($sex);
$registration->add($bdate);
$registration->add($email);
$registration->add($fb);
$registration->add($about);
$registration->add($id);
$registration->add($username);
$registration->add($send);

// push validator js class
$page->AddJS("jquery.form.js");
$page->AddJS("jquery.notify.js");
$page->AddJS("jquery-ui-1.10.1.custom.min.js");

$page->AddCSS("ui-lightness/jquery-ui-1.10.1.custom.css");

// add validator
$page->AddJQuery("$(\"#registration\").ajaxForm({dataType:'json', success: processReply});$(\"#birthdate\").datepicker({yearRange: \"-50:+0\", changeMonth: true, changeYear: true});"); 

// add form
$page->AddToBody($registration->get_form());
?>
