<?php
/*
 * Login control class. uses LDAP to authenticate
 */
//require_once '../ldap_class.php';
require_once '../cookie_class.php';
require_once '../model/db_base_class.php';
require_once '../model/db_config.php';

class login_ctrl
{
	private $ret;
	//private $ldap;
	private $post_data;
	private $cookie;
	private $db;

	public function __construct($data)
	{
		// save post data
		$this->post_data = $data;

		// ret array
		$this->ret = array();

		// ldap connection
		//$this->ldap = new ldap_login($this->post_data['username']);

		try
		{
			// Connessione a DB
			$this->db = new db_base(DB_USERNAME, DB_PASSWORD, DB_HOSTNAME, DB_NAME, DB_VER);

			// Cookie
			$this->cookie = new cookie_class();
		}
		catch (PDOException $e)
        {
            
        }
        catch (Exception $e)
        {
            
        }
	}

	public function process()
	{
		// connect to ldap server
		/*if ($this->ldap->connect())
		{
			// login
			$ret = $this->ldap->login($this->post_data['password']);

			// check state
			if ($ret == ldap_login::LOGIN_OK)
			{
				// set cookie
				// $this->pCookie->SetData($userdata);
				// redirect
				$this->ret['redirect'] = "index.php?page=main";
			}
			else if (($ret == ldap_login::LOGIN_SEARCH_ERR) || ($ret == ldap_login::LOGIN_RETRIVE_ERR))
				$this->ret['error'] = _("Errore di autenticazione, utente non trovato");
			else if ($ret == ldap_login::LOGIN_PASSWORD_ERR)
				$this->ret['error'] = _("Errore di autenticazione, password errata");
			else if ($ret == ldap_login::LOGIN_GEN_ERR)
				$this->ret['error'] = _("Errore di autenticazione, errore sconosciuto");
		}
		else
			$this->ret['error'] = _("Errore di autenticazione, ti preghiamo di riprovare più tardi");*/

		// is registered
		if ($this->db->user_is_registered($this->post_data['username']))
		{
			// get username data
			$userdata = $this->db->user_get_data($this->post_data['username']);

			// setcookie
			$this->cookie->SetData($userdata);

			// redirect to home
			$this->ret['redirect'] = "index.php?page=home";
		}
		else
			$this->ret['redirect'] = "index.php?page=register";
			
	}

	public function get_reply()
	{
		return $this->ret;
	}
}
?>
