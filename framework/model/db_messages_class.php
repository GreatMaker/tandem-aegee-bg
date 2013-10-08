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

define("MSG_PER_DAY", 5);

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
			// check message limit (5 per day)
			$query = "SELECT COUNT(*) FROM messages WHERE from_user_id = ? AND DATE(timestamp) = CURDATE()";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $from_id);
			$res->execute();

			$cnt = $res->fetchColumn();

			if ($cnt >= MSG_PER_DAY)
			{
				$this->dbConnection->SetError(_("Maximum number of messages sent for today!"));
				return;
			}

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

	public function message_get_buddies($user_id)
	{
		try
        {
			$query = "SELECT DISTINCT(users.id) FROM messages JOIN users ON messages.to_user_id = users.id WHERE messages.from_user_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_COLUMN);

			return $data;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user messages"));
        }
	}

	public function message_get_thread($user_id, $friend_id)
	{
		try
        {
			$query = "SELECT users.id, users.name, users.surname, users.facebook, users.gender, messages.message,  DATE_FORMAT(messages.timestamp, '%d/%m/%Y %H:%i') as timestamp FROM messages JOIN users ON messages.from_user_id = users.id WHERE ((messages.from_user_id = ? AND messages.to_user_id = ?) OR (messages.from_user_id = ? AND messages.to_user_id = ?)) ORDER BY messages.timestamp";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->bindParam(2, $friend_id);
			$res->bindParam(3, $friend_id);
			$res->bindParam(4, $user_id);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_ASSOC);

			return $data;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user interests"));
        }
	}
}
?>