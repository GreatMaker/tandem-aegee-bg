<?php
    require_once 'framework/page_class.php';
    require_once 'framework/page_list.php';

    // Page generator class
    $page = new page_class();

    // Show main if no page is specified
    $page_req = (!isset($_GET['page'])) ? HOME : $_GET['page'];

	// page requested
    $page->set_required_page($page_req);

    // Get page path
    $path_base = './framework/view/'; 
    $path      = $path_base;

    // folder
    if (isset($pages_list[$page_req]['folder']) && ($pages_list[$page_req]['folder'] != ""))
        $path .= $pages_list[$page_req]['folder'].'/';

    // file
    $path .= $pages_list[$page_req]['page'];

    // check file existance
    if (file_exists($path))
        require_once($path);
    else
        require_once($path_base.'page_not_found.php');

    // Display page
    $page->display();
?>