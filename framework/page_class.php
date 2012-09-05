<?php
/**
 * Page generation class
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

require('config.php');
require('cookie_class.php');

require('box_class.php');

class page_class
{
    protected   $pCookie;

    private     $page_html;
    private     $page_title;
    private     $page_css;
    private     $page_js;
    private     $page_foot_js;
    private     $page_body_extra;
    private     $page_menu;
    private     $page_body;
    private     $page_version;
    private     $page_footer;
    private     $page_logo;
    private     $page_company;
    private     $page_req;
    private     $page_sidebar;

    public function __construct()
    {
        // Init cookie
        //$this->pCookie = new cookie_class();
    }

    public function set_template($tpl_name)
    {
        // Reading template file
        if (file_exists($tpl_name))
            $this->page_html = file_get_contents($tpl_name);
        else
            die('Template file not found!');
    }

    // Set page title
    public function set_title($title)
    {
        $this->page_title = PAGE_TITLE_PREFIX.' - '.$title;
    }

    // Push JS into the page
    public function AddJS($js)
    {
        if (isset($this->page_js))
            $this->page_js .= "\n";

        $this->page_js .= "\t<script type=\"text/javascript\" src=\"js/".$js."\"></script>";
    }
    
    // Push CSS into the page
    public function AddCSS($css)
    {
        if (isset($this->page_css))
            $this->page_css .= "\n";

        $this->page_css .= "\t<link rel=\"stylesheet\" href=\"css/".$css."\" type=\"text/css\" />";
    }

    // populate sidebar
    public function add_sidebar_box($box)
    {
        if (isset($this->page_sidebar))
            $this->page_sidebar .= "\n";

        $this->page_sidebar .= $box;
    }

    // display page
    public function display()
    {
        // Caricamento JS menu (sono da caricare dopo per evitare conflitti con altri plugin jquery nella pagina)
        /*$this->AddJS('jquery.autron.js');
        $this->AddJS('hoverIntent.js');
	$this->AddJS('superfish.js');
        $this->AddJS('supersubs.js');*/

        // Impostazione titolo
        $this->page_html = str_replace("<%TITLE>", $this->page_title, $this->page_html);
        // Impostazione CSS
        $this->page_html = str_replace("<%CSS>", $this->page_css, $this->page_html);
        // Impostazione JS
        $this->page_html = str_replace("<%JS>", $this->page_js, $this->page_html);
        // Impostazione JS
        $this->page_html = str_replace("<%FOOTJS>", $this->page_foot_js, $this->page_html);
        // Impostazione logo
        $this->page_html = str_replace("<%LOGO>", $this->page_logo, $this->page_html);
        // Impostazione BODY extra
        $this->page_html = str_replace("<%BODY>", $this->page_body_extra, $this->page_html);
        // Impostazione Menu
        $this->page_html = str_replace("<%MENU>", $this->page_menu, $this->page_html);
        // Impostazione Body
        $this->page_html = str_replace("<%MAIN>", $this->page_body, $this->page_html);
        // Impostazione Versione
        $this->page_html = str_replace("<%VERSION>", $this->page_version, $this->page_html);
        // Impostazione Footer
        $this->page_html = str_replace("<%FOOTER>", $this->page_footer, $this->page_html);
        // Impostazione Nome societÃ 
        $this->page_html = str_replace("<%COMPANY>", $this->page_company, $this->page_html);
        // Sidebar
        $this->page_html = str_replace("<%SIDEBAR>", $this->page_sidebar, $this->page_html);

        // Output pagina
        echo $this->page_html;
    }
}
?>