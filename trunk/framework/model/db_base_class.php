<?php
/*
 * Database Base Model Class
 */

Abstract class ExtensionBridge
{
    // array containing all the extended classes
    private $_exts = array();
    public $_this;

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
                return call_user_method_array($method, $ext, $args);
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

        // add table classes
        //parent::addExt(new database_customers_table($this->base), "database_customers_table");
    }
}

class db_base extends PDO
{
    private $db_tables;

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
}
?>