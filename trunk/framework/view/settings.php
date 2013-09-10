<?php
/**
 * Settings View File
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

	$page->AddToBody("<h2>"._("Admin Settings")."</h2>");

	// get db connection
	$db_conn = null;
	$page->get_db($db_conn);
}
else
{
	// set page title
	$page->set_title('Settings');

	$page->AddToBody("<h2>"._("Settings")."</h2>");

	// get db connection
	$db_conn = null;
	$page->get_db($db_conn);

	$settings = new form_class("settings", "framework/controller/form_process.php", form_class::METHOD_POST, form_class::TYPE_FIELDSET);

	// name field
	$name = new form_field("name", _("Name"), true);
	$name->set_type(form_field::FIELD_TEXT);
	$name->set_value($user_data['name']);
	$name->set_style("width: 250px;");

	// surname field
	$surname = new form_field("surname", _("Surname"), true);
	$surname->set_type(form_field::FIELD_TEXT);
	$surname->set_value($user_data['surname']);
	$surname->set_style("width: 250px;");

	// sex field
	$sex = new form_field("sex", _("Sex"));
	$sex->set_type(form_field::FIELD_RADIO);
	$sex->set_value($user_data['gender']);
	$sex->set_data(array("M" => "M", "F" => "F"));

	// birthdate field
	$bdate = new form_field("birthdate", _("Birthdate"), true);
	$bdate->set_type(form_field::FIELD_TEXT);
	$bdate->set_value($user_data['birthdate']);
	$bdate->set_style("width: 80px;");

	// email field
	$email = new form_field("email", _("E-Mail"));
	$email->set_type(form_field::FIELD_TEXT);
	$email->set_value($user_data['email']);
	$email->set_style("width: 250px;");

	// facebook field
	$fb = new form_field("fb", _("Facebook"));
	$fb->set_type(form_field::FIELD_TEXT);
	$fb->set_value($user_data['facebook']);
	$fb->set_style("width: 350px;");

	// About you field
	$about = new form_field("about", _("About you"));
	$about->set_value($user_data['about']);
	$about->set_type(form_field::FIELD_TEXTAREA);

	// get user data
	$user_interests_data = $db_conn->user_interests_get($page->get_user_id());

	// interests field
	$interests = new form_field("interests", _("Interests"));
	$interests->set_type(form_field::FIELD_CHECKGRID);
	$interests->set_value($user_interests_data);
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
	$lang_speak_note->set_value(_("These are the languages you fluently speak, and which you want to tutor."));

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
	$id->set_value($user_data['id']);

	// hidden username field
	$username = new form_field("username", "");
	$username->set_type(form_field::FIELD_HIDDEN);
	$username->set_value($user_data['username']);

	$settings->add($name);
	$settings->add($surname);
	$settings->add($sex);
	$settings->add($bdate);
	$settings->add($email);
	$settings->add($fb);
	$settings->add($about);
	$settings->add($interests);
	
	$settings->fieldset_open(_("Languages you speak"), "f_lang_speak");
	$settings->add($lang_speak, true, true);
	$settings->add($lang_speak_note);
	$settings->fieldset_close();

	$settings->fieldset_open(_("Languages you want to learn"), "f_lang_learn");
	$settings->paragraph_open("p_lang_learn");
	$settings->add($lang_learn, false);
	$settings->add($lang_learn_level, false);
	$settings->paragraph_close();
	$settings->add($lang_learn_note);
	$settings->fieldset_close();

	$settings->add($id);
	$settings->add($username);
	$settings->add($send);

	// push validator js class
	$page->AddJS("jquery.form.js");
	$page->AddJS("jquery.notify.js");
	$page->AddJS("jquery-ui-1.10.1.custom.min.js");
	$page->AddJS("jquery-dynamic-form.js");

	$page->AddCSS("ui-lightness/jquery-ui-1.10.1.custom.css");

	// default spoken languages
	$user_speak_data = $db_conn->user_spoken_languages_get_by_id($page->get_user_id());

	$def_speak = "{\"f_lang_speak\": [{ \"p_lang_speak\" :[";
	foreach($user_speak_data as $id => $lang_code)
	{
		$def_speak .= "{\"lang_speak\":\"".$lang_code['lang_code']."\"},";
	}
	$def_speak .= "]}]}";
	
	// default learn languages
	$user_learn_data = $db_conn->user_learn_languages_get_by_id($page->get_user_id());

	$def_learn = "{\"f_lang_learn\": [{ \"p_lang_learn\" :[";
	foreach($user_learn_data as $id => $lang_code)
	{
		$def_learn .= "{\"lang_learn\":\"".$lang_code['lang_code']."\", \"lang_learn_level\":\"".$lang_code['level']."\"},";
	}
	$def_learn .= "]}]}";

	// add validator
	$page->AddJQuery("$(\"#settings\").ajaxForm({dataType:'json', success: processReply});
					  $(\"#birthdate\").datepicker({yearRange: \"-50:+0\", changeMonth: true, changeYear: true, dateFormat: \"dd/mm/yy\"});
					  var speakForm = $(\"#f_lang_speak\").dynamicForm({formPrefix:\"mainForm\"});
					  var learnForm = $(\"#f_lang_learn\").dynamicForm({formPrefix:\"mainForm\"});
					  $(\"#p_lang_speak\").dynamicForm({plusSelector: \"#plus_speak\", minusSelector: \"#minus_speak\", limit: 5});
					  $(\"#p_lang_learn\").dynamicForm({plusSelector: \"#plus_learn\", minusSelector: \"#minus_learn\", limit: 5});
					  speakForm.dynamicForm('inject', ".$def_speak.");
					  learnForm.dynamicForm('inject', ".$def_learn.");
	"); 

	// add form
	$page->AddToBody($settings->get_form());
}
?>
