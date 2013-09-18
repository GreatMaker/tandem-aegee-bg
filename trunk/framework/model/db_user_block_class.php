<?php
/*
 * User block class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class user_block_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function user_block_add($user_id, $blocked_id)
	{
		try
        {
			$query = "INSERT INTO user_block (user_id, block_user_id) VALUES (?, ?)";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->bindParam(2, $blocked_id);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error adding block"));
        }
	}

	public function user_block_remove($user_id, $blocked_id)
	{
		try
        {
			$query = "DELETE FROM user_block WHERE user_id = ? AND block_user_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->bindParam(2, $blocked_id);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error removing block"));
        }
	}

	public function user_block_is_blocked($user_id, $blocked_id)
	{
		try
        {
			$query = "SELECT * FROM user_block WHERE user_id = ? AND block_user_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->bindParam(2, $blocked_id);

			$res->execute();
			
			if ($res->fetchColumn() > 0)
				return true;

			return false;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user block get"));
        }
	}
}
?>
