<?php
/**
 * Cookies handling
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */
class cookie_class
{
    private $do_login;

    public function __construct()
    {
        // Avvio sessione
        session_start();

        // controllo esistenza cookie
        if (!isset($_COOKIE['tandem']['u']) || !isset($_COOKIE['tandem']['p']))
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
        $this->SetPassword($userdata['password']);
        $this->SetScreenResolution($userdata['screen_resolution']);
    }

    public function GetUsername()
    {
        return $_COOKIE['tandem']['u'];
    }

    public function SetUsername($username)
    {
        setcookie("tandem[u]", trim(md5($username)), time() + (3600 * 24));
    }

    public function GetPassword()
    {
        return $_COOKIE['tandem']['p'];
    }

    public function SetPassword($password)
    {
        setcookie("tandem[p]", trim(md5($password)), time() + (3600 * 24)); // giÃ  in MD5
    }

    public function GetScreenResolution()
    {
        return $_COOKIE['tandem']['res'];
    }

    public function SetScreenResolution($screen_res)
    {
        setcookie("tandem[res]", $screen_res, time() + (3600 * 24));
    }

    public function GetLanguage()
    {
        return $_COOKIE['tandem']['lang'];
    }

    protected function UpdateCookies()
    {
        // cookie scadenza +24 ore
        setcookie("tandem[u]",   $_COOKIE['tandem']['u'],   time() + (3600 * 24));
        setcookie("tandem[p]",   $_COOKIE['tandem']['p'],   time() + (3600 * 24));
        setcookie("tandem[res]", $_COOKIE['tandem']['res'], time() + (3600 * 24));
    }

    public function Logout()
    {
        // elimina cookie
        setcookie ("tandem[u]",   "", time() - 3600);
        setcookie ("tandem[p]",   "", time() - 3600);
        setcookie ("tandem[res]", "", time() - 3600);
    }
}
?>
