<?php
/*
 * LDAP login class
 * using both matricola or username of the student
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */
require_once 'config.php';

class ldap_login
{
    private $login_type = 0;
    private $ldapconn;
    private $ldap_uname = "";
    
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
                        return self::LOGIN_OK;
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
}
?>
