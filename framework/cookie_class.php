<?php
/**
 * Cookies handling
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */
class cookie_class
{
    private $do_login;

    public function __construct()
    {
        // Avvio sessione
        session_start();

        // controllo esistenza cookie
        if (!isset($_COOKIE['tandem']['u']))
        {
            $this->do_login = true;
            return;
        }
        else
            $this->UpdateCookies(); // update cookies

        $this->do_login = false;
    }

    public function LoginNeeded()
    {
        return $this->do_login;
    }

    public function GetData()
    {
        return $_COOKIE['tandem'];
    }

    public function SetData($userdata)
    {
        $this->SetUsername($userdata['username']);
        //$this->SetPassword($userdata['password']);
    }

    public function GetUsername()
    {
        return isset($_COOKIE['tandem']['u']) ? $_COOKIE['tandem']['u'] : false;
    }

    public function SetUsername($username)
    {
        setcookie("tandem[u]", trim(md5($username)), time() + (3600 * 24), "/new", "tandem.unibg.it");
    }

    public function GetPassword()
    {
        return $_COOKIE['tandem']['p'];
    }

    public function SetPassword($password)
    {
        setcookie("tandem[p]", trim(md5($password)), time() + (3600 * 24), "/new", "tandem.unibg.it"); // già  in MD5
    }

    public function GetLanguage()
    {
        return isset($_COOKIE['tandem']['lang']) ? $_COOKIE['tandem']['lang'] : false;
    }

    protected function UpdateCookies()
    {
        // cookie scadenza +24 ore
        setcookie("tandem[u]",    $_COOKIE['tandem']['u'],   time() + (3600 * 24), "/new", "tandem.unibg.it");
        //setcookie("tandem[p]",    $_COOKIE['tandem']['p'],   time() + (3600 * 24));
        //setcookie("tandem[lang]", $_COOKIE['tandem']['lang'], time() + (3600 * 24));
    }

    public function Logout()
    {
        // elimina cookie
        setcookie ("tandem[u]",   "", time() - 3600, "/new", "tandem.unibg.it");
        //setcookie ("tandem[p]",   "", time() - 3600);
        //setcookie ("tandem[lang]", "", time() - 3600);
    }
}
?>
