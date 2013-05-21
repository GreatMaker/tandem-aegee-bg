<?php
/*
 * Abstract class for Controller classes
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

require_once '../model/db_base_class.php';
require_once '../model/db_config.php';

abstract class ctrl_abstract
{
	// constructor
    abstract public function		__construct($data);

	// process data
    abstract public function		process();

	// reply to user
	abstract public function		get_reply();

	// connection function
	public function					getDBConnection(&$pConnection)
	{
		try
		{
			// Connessione a DB
			$pConnection = new db_base(DB_USERNAME, DB_PASSWORD, DB_HOSTNAME, DB_NAME, DB_VER);
		}
		catch (PDOException $e)
        {
            return FALSE;
        }
        catch (Exception $e)
        {
            return FALSE;
        }

		return TRUE;
	}
}
?>