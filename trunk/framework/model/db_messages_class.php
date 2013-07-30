<?php
/*
 * Messages table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class messages_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function message_add($from_id, $to_id, $message)
	{
		try
        {
			$query = "INSERT INTO messages (from_user_id, to_user_id, message) VALUES (?, ?, ?)";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $from_id);
			$res->bindParam(2, $to_id);
			$res->bindParam(3, $message);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error sending message")." - ".$e->getMessage());
        }
	}
}
?>
