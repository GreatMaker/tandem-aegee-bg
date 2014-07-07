<?php
/**
 * Erasmus Buddy View File
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
$page->set_title('Erasmus Buddy Program');

$page->AddToBody("<h2>"._("Erasmus Buddy Program")."</h2>");

$page->AddToBody("Sei appena tornato dall'erasmus?
Vuoi entrare in contatto con gli studenti stranieri che stanno arrivando a Bergamo, aiutarli e magari trovare un nuovo amico?<br /><br />
Inserisci i tuoi dati qui sotto e ti ricontatteremo:<br /><br />");

$buddy = new form_class("erasmus_buddy", "framework/controller/form_process.php", form_class::METHOD_POST, form_class::TYPE_FIELDSET);

// name field
$name = new form_field("name", _("Name"));
$name->set_type(form_field::FIELD_TEXT);
$name->set_style("width: 250px;");

// surname field
$surname = new form_field("surname", _("Surname"));
$surname->set_type(form_field::FIELD_TEXT);
$surname->set_style("width: 250px;");

// email field
$email = new form_field("email", _("E-Mail"));
$email->set_type(form_field::FIELD_TEXT);
$email->set_style("width: 250px;");

// faculty field
$faculty = new form_field("faculty", _("Faculty"));
$faculty->set_type(form_field::FIELD_TEXT);
$faculty->set_style("width: 250px;");

// Languages
$languages = new form_field("languages", _("Languages"));
$languages->set_type(form_field::FIELD_TEXTAREA);

// send button
$send = new form_field("send", "");
$send->set_type(form_field::FIELD_BUTTON);
$send->set_value(_("Send"));

// add to form
$buddy->add($name);
$buddy->add($surname);
$buddy->add($email);
$buddy->add($faculty);
$buddy->add($languages);
$buddy->add($send);

// push validator js class
$page->AddJS("jquery.form.js");
$page->AddJS("jquery.notify.js");
$page->AddJS("jquery-ui-1.10.1.custom.min.js");
$page->AddJS("jquery-dynamic-form.js");

$page->AddCSS("ui-lightness/jquery-ui-1.10.1.custom.css");

// add validator
$page->AddJQuery("$(\"#erasmus_buddy\").ajaxForm({dataType:'json', success: processReply});"); 

// add form
$page->AddToBody($buddy->get_form());
?>
