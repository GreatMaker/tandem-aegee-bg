<?php
/**
 * Main View File
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

// set template file
$page->set_template('framework/templates/main.tpl');

// set page title
$page->set_title('Home');

// box test
$box = new box_class("Test");
$box->add_content("<p style=\"margin: 0;\">Aenean</p>");

$page->add_sidebar_box($box->get_box_data());
?>
