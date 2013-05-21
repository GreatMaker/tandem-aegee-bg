<?php
/*
 * Login control class. uses LDAP to authenticate
 */
require_once '../config.php';
require_once '../ldap_class.php';
require_once '../cookie_class.php';
require_once '../model/db_base_class.php';
require_once '../model/db_config.php';
require_once 'controller_interface.php';

class login_ctrl extends ctrl_abstract
{
	private $ret;
	private $ldap;
	private $post_data;
	private $cookie;
	private $db;
	private $load = false;
	private $err;

	public function __construct($data)
	{
		// save post data
		$this->post_data = $data;

		// ret array
		$this->ret = array();

		try
		{
			// Cookie
			$this->cookie = new cookie_class();

			if (LDAP_ENABLE == true)
			{
				// ldap connection
				$this->ldap = new ldap_login($this->post_data['username']);
			}

			// Connessione a DB
			$this->db = new db_base(DB_USERNAME, DB_PASSWORD, DB_HOSTNAME, DB_NAME, DB_VER);
		}
		catch (PDOException $e)
        {
			$this->ret['error'] = _("Authentication error, user not found");
            throw new Exception($e);
        }
        catch (Exception $e)
        {
			$this->ret['error'] = _("Authentication error, user not found");
            throw $e;
        }
	}

	public function process()
	{
		$b_ldap_ok = false;

		if (LDAP_ENABLE == true)
		{
			try
			{
				// connect to ldap server
				if ($this->ldap->connect())
				{
					// login
					$ret = $this->ldap->login($this->post_data['password']);

					// check state
					if ($ret == ldap_login::LOGIN_OK)
					{
						$b_ldap_ok = true;
					}
					else if (($ret == ldap_login::LOGIN_SEARCH_ERR) || ($ret == ldap_login::LOGIN_RETRIVE_ERR))
					{
						//$this->ret['error'] = _("Errore di autenticazione, utente non trovato");
						$b_ldap_ok = false;
					}
					else if ($ret == ldap_login::LOGIN_PASSWORD_ERR)
					{
						$this->ret['error'] = _("Authentication error, wrong password");
						return;
					}
					else if ($ret == ldap_login::LOGIN_GEN_ERR)
					{
						$this->ret['error'] = _("Authentication error, unknown error");
						return;
					}
				}
				else
				{
					$this->ret['error'] = _("Authentication error, please try again later");
					return;
				}
			}
			catch (Exception $e)
			{
				$this->ret['error'] = $e;
				return;
			}
		}

		try
		{
			// is registered
			if ($this->db->user_is_registered($this->post_data['username']))
			{
				// fall back to normal login, check auth
				if ($b_ldap_ok == false)
				{
					if (!$this->db->user_auth($this->post_data['username'], $this->post_data['password']))
					{
						$this->ret['error'] = _("Authentication error, wrong password");
						return;
					}	
				}

				// get username data
				$userdata = $this->db->user_get_data($this->post_data['username']);

				// setcookie
				$this->cookie->SetData($userdata);

				// redirect to home
				$this->ret['redirect'] = "index.php?page=home";
			}
			else
			{
				if (LDAP_ENABLE == true)
				{
					// get ldap data
					$ldap_data = $this->ldap->get_data();

					// save to session and finish registration
					$_SESSION['id']			= $ldap_data['id'];
					$_SESSION['username']	= $ldap_data['username'];
					$_SESSION['name']		= $ldap_data['name'];
					$_SESSION['surname']	= $ldap_data['surname'];

					// redirect
					$this->ret['redirect'] = "index.php?page=register";
				}
				else
				{
					$this->ret['error'] = _("Authentication error");
					return;
				}
			}
		}
		catch (PDOException $e)
        {
			$this->ret['error'] = $e;
			return;
		}
	}

	public function get_reply()
	{
		return $this->ret;
	}
}
?>
