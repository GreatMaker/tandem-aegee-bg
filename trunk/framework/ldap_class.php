<?php
/*
 * LDAP login class
 * using both matricola or username of the student
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */
require_once 'config.php';

class ldap_login
{
    private $login_type = 0;
    private $ldapconn;
    private $ldap_uname = "";
	private $ldap_data;
    
	// Login type
    const LOGIN_MATRICOLA		= 0;
    const LOGIN_USERNAME		= 1;

    // Login states
    const LOGIN_OK = 0;
    const LOGIN_SEARCH_ERR		= 1;
    const LOGIN_RETRIVE_ERR		= 2;
    const LOGIN_PASSWORD_ERR	= 3;
    const LOGIN_GEN_ERR			= 4;

    public function __construct($username)
    {
        // type selection
        if (is_numeric($username))
            $this->login_type = self::LOGIN_MATRICOLA;
        else
            $this->login_type = self::LOGIN_USERNAME;

        $this->ldap_uname = $username;
		$this->ldap_data  = array();
    }

    public function connect()
    {
        // connect to ldap server
        $this->ldapconn = ldap_connect(LDAP_LOGIN_SERVER);

        if (!$this->ldapconn)
            return false;

        return true;
    }

    public function login($password)
    {
        if ($this->login_type == self::LOGIN_MATRICOLA)
            $filter = 'uid='.$this->ldap_uname;
        else
            $filter = 'mail='.$this->ldap_uname."@studenti.unibg.it";

        try
        {
            $s = @ldap_search($this->ldapconn, 'ou=studenti,cn=users,dc=unibg,dc=it', $filter);

            if ($s)
            {
                $ret = @ldap_get_entries($this->ldapconn, $s);

                if ($ret[0])
                {
                    if (ldap_bind($this->ldapconn, $ret[0]['dn'], $password))
					{
						$this->ldap_data['id']			= $ret[0]['uid'][0];
						$this->ldap_data['username']	= substr($ret[0]['mail'][0] , 0, strrpos($ret[0]['mail'][0], "@"));
						$this->ldap_data['name']		= $ret[0]['givenname'][0];
						$this->ldap_data['surname']		= $ret[0]['sn'][0];

                        return self::LOGIN_OK;
					}
                    else
                        return self::LOGIN_PASSWORD_ERR;
                }
                else
                    return self::LOGIN_RETRIVE_ERR;
            }
            else
                return self::LOGIN_SEARCH_ERR;
        }
        catch(Exception $e)
        {
            return self::LOGIN_GEN_ERR;
        }
    }

	public function get_data()
	{
		return $this->ldap_data;
	}
}
?>
