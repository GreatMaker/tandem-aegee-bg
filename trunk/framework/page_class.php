<?php
/**
 * Page generation class
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

require('config.php');
require('cookie_class.php');
require('language_class.php');
require('model/db_base_class.php');

require('box_class.php');

// pages
define("HOME",      "home");
define("REGISTER",	"register");

class page_class
{
    protected   $pCookie;
	protected	$pLang;
	protected	$pConnection;

	private		$bLoginNeeded = false;
	private		$bContentsDB = false;

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
	private		$page_social;
	private		$page_plainjs;

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

	public function is_user_logged()
	{
		return ($this->bLoginNeeded) ? false : true;
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

        $this->page_js .= "<script type=\"text/javascript\" src=\"/include/js/".$js."\"></script>";
    }
    
    // Push CSS into the page
    public function AddCSS($css)
    {
        if (isset($this->page_css))
            $this->page_css .= "\n";

        $this->page_css .= "<link rel=\"stylesheet\" href=\"/include/css/".$css."\" type=\"text/css\" />";
    }

	// Push jquery init into the page
    public function AddJQuery($data)
    {
        if (isset($this->page_jquery))
            $this->page_jquery .= "\n";

        $this->page_jquery .= "\t".$data;
    }
	
	public function AddJSPlain($data)
    {
        if (isset($this->page_plainjs))
            $this->page_plainjs .= "\n";

        $this->page_plainjs .= "\t".$data;
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

		// force flag
		$this->bContentsDB = false;
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

	private function add_social_toolbar()
	{
		$str_err = "";

		// get toolbar
		$data = $this->pConnection->get_social_toolbar();
		
		if ($this->pConnection->GetError($str_err) == false)
		{
			$social_content = "";

			foreach ($data as $id => $social_data)
			{
				$link = $social_data['link_1'].$social_data['link_2'];

				$social_content .= "<a href=\"".$link."\" title=\"".$social_data['name_ext']."\"><img src=\"/img/icons/social/".$social_data['icon']."\" alt=\"".$social_data['name_ext']."\" style=\"border: 0;\" /></a>&nbsp;";
			}

			$this->page_social = $social_content;
		}
	}

	private function add_top_menu()
	{
		$str_err = "";

		// get toolbar
		$data = $this->pConnection->get_pages_top_menu();

		if ($this->pConnection->GetError($str_err) == false)
		{
			$menu_content = "";

			foreach ($data as $id => $menu_data)
			{
				$link = "index.php?page=".$menu_data['page'];

				$menu_content .= "<li><a href=\"".$link."\" title=\"".$menu_data['name']."\">".$menu_data['name']."</a></li>\n";
			}

			$this->page_menu = $menu_content;
		}
	}

	public function add_db_contents($page)
	{
		$str_err = "";

		// force flag
		$this->bContentsDB = true;

		// get contents for the selected page
		$data = $this->pConnection->get_contents_page($page);

		if ($this->pConnection->GetError($str_err) == false)
		{
			foreach ($data as $id => $content_data)
			{
				/*$this->page_body .= "<div id=\"content\">\n";
				$this->page_body .= "\t".$content_data['html']."\n";
				$this->page_body .= "</div>\n";*/
				
				// HTML5
				$this->page_body .= "<article>\n";
				$this->page_body .= "\t".$content_data['html']."\n";
				$this->page_body .= "</article>\n";
			}
		}
	}

	public function get_db(&$db_conn)
	{
		$db_conn = $this->pConnection;
	}

	public function get_user_data()
	{
		// username
		$username = $this->pCookie->GetUsername();

		return $this->pConnection->user_get_data($username, true);
	}

	public function get_user_id()
	{
		// username
		$username = $this->pCookie->GetUsername();

		return $this->pConnection->user_get_id_by_username($username, true);
	}

	public function logout()
	{
		$this->pCookie->Logout();
	}

    // display page
    public function display()
    {		
		// Insert login sidebar box if needed
		if ($this->bLoginNeeded == true && $this->page_req != REGISTER)
			$this->add_sidebar_login_box();
		else if ($this->page_req != REGISTER)
			$this->add_sidebar_userdetails();

		// Insert social toolbar in header
		$this->add_social_toolbar();
		
		// Insert top menu
		$this->add_top_menu();

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
        // Impostazione Footer
        $this->page_html = str_replace("<%FOOTER>", $this->page_footer, $this->page_html);
        // Impostazione Nome societÃ 
        $this->page_html = str_replace("<%COMPANY>", $this->page_company, $this->page_html);
        // Sidebar
        $this->page_html = str_replace("<%SIDEBAR>", $this->page_sidebar, $this->page_html);
		// jQuery on load
		$this->page_html = str_replace("<%JQUERY>", $this->page_jquery, $this->page_html);
		// JS Plain
		$this->page_html = str_replace("<%PLAINJS>", $this->page_plainjs, $this->page_html);
		// Social toolbar
		$this->page_html = str_replace("<%SOCIAL>", $this->page_social, $this->page_html);

		// page content (db or not db...)
		if ($this->bContentsDB == true)
		{
			
		}
		else
		{
			// fix div
			//$this->page_body = "<div id=\"content\">".$this->page_body."</div>";
			$this->page_body = "<article>".$this->page_body."</article>"; // HTML5
		}
		
		// set page content
		$this->page_html = str_replace("<%MAIN>", $this->page_body, $this->page_html);

        // Output pagina
        echo $this->page_html;
    }
}
?>