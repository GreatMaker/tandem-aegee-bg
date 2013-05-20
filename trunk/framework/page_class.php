<?php
/**
 * Page generation class
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

require('config.php');
require('cookie_class.php');
require('language_class.php');
require('page_list.php');
require('model/db_base_class.php');

require('box_class.php');

class page_class
{
    protected   $pCookie;
	protected	$pLang;
	protected	$pConnection;

	private		$bLoginNeeded = false;

    private     $page_html;
    private     $page_title;
    private     $page_css;
    private     $page_js;
	private		$page_head;
    private     $page_foot_js;
    private     $page_body_extra;
    private     $page_menu;
    private     $page_body;
    private     $page_footer;
    private     $page_logo;
    private     $page_company;
    private     $page_req;
    private     $page_sidebar;
	private		$page_jquery;

    public function __construct()
    {
        // Init cookie
        $this->pCookie = new cookie_class();

		// set login needed flag
		if ($this->pCookie->LoginNeeded())
			$this->bLoginNeeded = true;

		// Init language
        $this->pLang = new language_class($this->pCookie->GetLanguage());

		try
		{
			// Connessione a DB
			$this->pConnection = new db_base(DB_USERNAME, DB_PASSWORD, DB_HOSTNAME, DB_NAME, DB_VER);
		}
		catch (PDOException $e)
        {
            
        }
        catch (Exception $e)
        {
            
        }
    }

    public function set_template($tpl_name)
    {
        // Reading template file
        if (file_exists($tpl_name))
            $this->page_html = file_get_contents($tpl_name);
        else
            die('Template file not found!');
    }

	public function set_required_page($page)
	{
		$this->page_req = $page;
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

        $this->page_js .= "<script type=\"text/javascript\" src=\"include/js/".$js."\"></script>";
    }
    
    // Push CSS into the page
    public function AddCSS($css)
    {
        if (isset($this->page_css))
            $this->page_css .= "\n";

        $this->page_css .= "<link rel=\"stylesheet\" href=\"include/css/".$css."\" type=\"text/css\" />";
    }

	// Push jquery init into the page
    public function AddJQuery($data)
    {
        if (isset($this->page_jquery))
            $this->page_jquery .= "\n";

        $this->page_jquery .= "\t".$data;
    }

	public function AddHead($data)
	{
		if (isset($this->page_head))
            $this->page_head .= "\n";

        $this->page_head .= "\t".$data;
	}

	public function AddToBody($str)
    {
        $this->page_body .= $str;
    }

    // populate sidebar
    public function add_sidebar_box($box)
    {
        if (isset($this->page_sidebar))
            $this->page_sidebar .= "\n";

        $this->page_sidebar .= $box;
    }

	private function add_sidebar_login_box()
	{
		// create login box
		$box = new login_box_class(_("Login"), "login", $this);

		// append data
		self::add_sidebar_box($box->get_box_data());
	}

	private function add_sidebar_facebook()
	{
		// create login box
		$box = new facebook_box_class(_("Facebook"), $this);

		// append data
		self::add_sidebar_box($box->get_box_data());
	}

	private function add_sidebar_userdetails()
	{
		// create login box
		$box = new userdetails_box_class(_("User"), $this);

		// append data
		self::add_sidebar_box($box->get_box_data());
	}

	public function get_db(&$db_conn)
	{
		$db_conn = $this->pConnection;
	}

	public function get_user_data()
	{
		// username
		$username = $this->pCookie->GetUsername();

		return $this->pConn->user_get_data($username, true);
	}

	public function logout()
	{
		$this->pCookie->Logout();
	}

    // display page
    public function display()
    {
        // Caricamento JS menu (sono da caricare dopo per evitare conflitti con altri plugin jquery nella pagina)
        /*$this->AddJS('jquery.autron.js');
        $this->AddJS('hoverIntent.js');
	$this->AddJS('superfish.js');
        $this->AddJS('supersubs.js');*/
		
		// Insert login sidebar box if needed
		if ($this->bLoginNeeded == true && $this->page_req != REGISTER)
			$this->add_sidebar_login_box();
		else if ($this->page_req != REGISTER)
			$this->add_sidebar_userdetails();

		// Insert facebook sidebar box
		$this->add_sidebar_facebook();

        // Set page title
        $this->page_html = str_replace("<%TITLE>", $this->page_title, $this->page_html);
        // Set CSS
        $this->page_html = str_replace("<%CSS>", $this->page_css, $this->page_html);
        // Set JS
        $this->page_html = str_replace("<%JS>", $this->page_js, $this->page_html);
		// Set Head
        $this->page_html = str_replace("<%HEAD>", $this->page_head, $this->page_html);
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
        // Impostazione Footer
        $this->page_html = str_replace("<%FOOTER>", $this->page_footer, $this->page_html);
        // Impostazione Nome societÃ 
        $this->page_html = str_replace("<%COMPANY>", $this->page_company, $this->page_html);
        // Sidebar
        $this->page_html = str_replace("<%SIDEBAR>", $this->page_sidebar, $this->page_html);
		// jQuery on load
		$this->page_html = str_replace("<%JQUERY>", $this->page_jquery, $this->page_html);

        // Output pagina
        echo $this->page_html;
    }
}
?>