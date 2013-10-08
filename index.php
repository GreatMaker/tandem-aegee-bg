<?php
/**
 * Main Index File
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */
    require_once 'framework/page_class.php';

    // Page generator class
    $page = new page_class();

    // Show main if no page is specified
    $page_req = (!isset($_GET['page'])) ? HOME : $_GET['page'];

	// page requested
    $page->set_required_page($page_req);

    // Get page path
    $path_base = './framework/view/';

	// admin redirect
	if (isset($_GET['admin']))
		$path_base .= "admin/";

	$path = $path_base;

    // file
    $path .= $page_req.".php";

    // check file existance
    if (file_exists($path))
        require_once($path);
    else
        require_once($path_base.'page_not_found.php');

    // Display page
    $page->display();
?>