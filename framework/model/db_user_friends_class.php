<?php
/*
 * Users table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class user_friends_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function user_friends_add($user_id, $friend_id)
	{
		try
        {
			$query = "INSERT INTO user_friends (user_id, friend_id) VALUES (?, ?)";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->bindParam(2, $friend_id);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error adding friend"));
        }
	}

	public function user_friends_remove($user_id, $friend_id)
	{
		try
        {
			$query = "DELETE FROM user_friends WHERE user_id = ? AND friend_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->bindParam(2, $friend_id);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error removing friend"));
        }
	}

	public function user_friends_get($user_id)
	{
		try
        {
			$query = "SELECT * FROM user_friends WHERE user_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->execute();

			return $res->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user friends"));
        }
	}

	public function user_friends_is_friend($user_id, $friend_id)
	{
		try
        {
			$query = "SELECT * FROM user_friends WHERE user_id = ? AND friend_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->bindParam(2, $friend_id);

			$res->execute();
			
			if ($res->fetchColumn() > 0)
				return true;

			return false;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user friends"));
        }
	}
}
?>
