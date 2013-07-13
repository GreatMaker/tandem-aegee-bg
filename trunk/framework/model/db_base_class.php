<?php
/*
 * Database Base Model Class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

require_once 'db_base_class.php';
require_once 'db_users_class.php';
require_once 'db_languages_class.php';
require_once 'db_user_languages_class.php';
require_once 'db_social_toolbar_class.php';
require_once 'db_top_menu_class.php';
require_once 'db_interests_class.php';
require_once 'db_user_interests_class.php';

Abstract class ExtensionBridge
{
    // array containing all the extended classes
    private $_exts = array();
    public  $_this;

    function __construct() {$_this = $this;}

    public function addExt($object, $name)
    {
        if (!array_key_exists($name, $this->_exts))
            $this->_exts[$name] = $object;
    }

    public function __get($varname)
    {
        foreach($this->_exts as $name => $ext)
        {
            if (property_exists($ext, $varname))
                return $ext->$varname;
        }
    }

    public function __call($method, $args)
    {
        foreach($this->_exts as $name => $ext)
        {
            if (method_exists($ext, $method))
                return call_user_func_array(array($ext, $method), $args);
        }
        throw new Exception("This Method {$method} doesn't exists");
    }
}

class database_tables extends ExtensionBridge
{
    private $base;

    public function __construct($parent)
    {
        $this->base = $parent;

        // add table users
        parent::addExt(new users_table($this->base), "users_table");
		parent::addExt(new languages_table($this->base), "languages_table");
		parent::addExt(new user_languages_table($this->base), "user_languages_table");
		parent::addExt(new social_toolbar_table($this->base), "social_toolbar_table");
		parent::addExt(new top_menu_table($this->base), "top_menu_table");
		parent::addExt(new interests_table($this->base), "interests_table");
		parent::addExt(new user_interests_table($this->base), "user_interests_table");
    }
}

class db_base extends PDO
{
    private $db_tables;
	private $error_str = "";

    public function __construct($dbusername, $dbpasswd, $dbhostname, $dbname, $dbversion)
    {
        // Connecting to the database
        try
        {
            $dsn        = "mysql:host=".$dbhostname.";dbname=".$dbname;
            $options    = array(PDO::MYSQL_ATTR_INIT_COMMAND   => "SET NAMES utf8", 
                                PDO::ATTR_PERSISTENT           => false);

            // Open connection
            parent::__construct($dsn, $dbusername, $dbpasswd, $options);

            // Error handling
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Disable auto commit
			$this->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);

            // Allocation of tables classes through the extension bridge
            $this->db_tables  = new database_tables($this);
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    // Catch unknow calls and forward to modules
    public function __call($method, $args)
    {
        try
        {
            return call_user_func_array(array($this->db_tables, $method), $args);
        }
        catch (Exception $e)
        {
            throw new PDOException($e);
        }
    }

	public function SetError($str_err)
    {
        $this->error_str = $str_err;
    }

    public function GetError(&$str_err)
    {
        if ($this->error_str == "")
            return false;

        $str_err = $this->error_str;

        $this->error_str = "";
        return true;
    }
}
?>
