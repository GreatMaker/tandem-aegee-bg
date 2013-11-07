<?php
/**
 * Register View File
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
$page->set_title('Register');

// get db connection
$db_conn = null;
$page->get_db($db_conn);

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

// Interests field
$interests = new form_field("interests", _("Interests"));
$interests->set_type(form_field::FIELD_CHECKGRID);
$interests->set_data($db_conn->get_interests());

// Languages you speak fields
$lang_speak = new form_field("lang_speak", _("Language"));
$lang_speak->set_type(form_field::FIELD_OPTION);
$lang_speak->set_data($db_conn->languages_get_data());
$lang_speak->set_style("width:180px;");
$lang_speak->set_extra("<span class=\"inline\" style=\"vertical-align: middle;\"> <a id=\"minus_speak\" style=\"vertical-align: middle;\"><img src=\"img/icons/minus.png\" height=\"20\" width=\"20\"></a> <a id=\"plus_speak\" style=\"vertical-align: middle;\"><img src=\"img/icons/plus.png\"  height=\"20\" width=\"20\"></a></span>");

// Languages you speak note
$lang_speak_note = new form_field("lang_speak_note");
$lang_speak_note->set_type(form_field::FIELD_NOTE);
$lang_speak_note->set_value(_("These are the languages you are mother tongue, and which you want to tutor."));

// Language you want to learn
$lang_learn = new form_field("lang_learn", _("Language"));
$lang_learn->set_type(form_field::FIELD_OPTION);
$lang_learn->set_data($db_conn->languages_get_data());
$lang_learn->set_style("width:180px;");
$lang_learn->set_extra("&nbsp;");

$lang_learn_level = new form_field("lang_learn_level", "");
$lang_learn_level->set_type(form_field::FIELD_OPTION);
$lang_learn_level->set_data($db_conn->languages_get_levels());
$lang_learn_level->set_extra("<span class=\"inline\" style=\"vertical-align: middle;\"> <a id=\"minus_learn\" style=\"vertical-align: middle;\"><img src=\"img/icons/minus.png\" height=\"20\" width=\"20\"></a> <a id=\"plus_learn\" style=\"vertical-align: middle;\"><img src=\"img/icons/plus.png\"  height=\"20\" width=\"20\"></a></span>");

// Languages you want to learn note
$lang_learn_note = new form_field("lang_learn_note");
$lang_learn_note->set_type(form_field::FIELD_NOTE);
$lang_learn_note->set_value(_("These are the languages you want to learn, set your skills level as well."));

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
$registration->add($interests);

$registration->fieldset_open(_("Languages you speak as native"), "f_lang_speak");
$registration->add($lang_speak, true, true);
$registration->add($lang_speak_note);
$registration->fieldset_close();

$registration->fieldset_open(_("Languages you want to learn"), "f_lang_learn");
$registration->paragraph_open("p_lang_learn");
$registration->add($lang_learn, false);
$registration->add($lang_learn_level, false);
$registration->paragraph_close();
$registration->add($lang_learn_note);
$registration->fieldset_close();

$registration->add($id);
$registration->add($username);
$registration->add($send);

// push validator js class
$page->AddJS("jquery.form.js");
$page->AddJS("jquery.notify.js");
$page->AddJS("jquery-ui-1.10.1.custom.min.js");
$page->AddJS("jquery-dynamic-form.js");

$page->AddCSS("ui-lightness/jquery-ui-1.10.1.custom.css");

// add validator
$page->AddJQuery("$(\"#registration\").ajaxForm({dataType:'json', success: processReply});
				  $(\"#birthdate\").datepicker({yearRange: \"-50:+0\", changeMonth: true, changeYear: true, dateFormat: \"dd/mm/yy\"});
				  $(\"#p_lang_speak\").dynamicForm({plusSelector: \"#plus_speak\", minusSelector: \"#minus_speak\", limit: 5});
				  $(\"#p_lang_learn\").dynamicForm({plusSelector: \"#plus_learn\", minusSelector: \"#minus_learn\", limit: 5});"); 

// add form
$page->AddToBody($registration->get_form());
?>
