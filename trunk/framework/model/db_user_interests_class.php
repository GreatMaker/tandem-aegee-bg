<?php
/*
 * User Interests table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class user_interests_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function user_interests_get($user_id)
	{
		try
        {
			$query = "SELECT interest_id FROM user_interests WHERE user_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_COLUMN);

			return $data;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user interests"));
        }
	}

	public function user_interests_add($data, $user_id)
	{
		try
		{
			foreach ($data['interests'] as $id => $int_id)
			{
				$query = "INSERT INTO user_interests (user_id, interest_id) VALUES (?, ?)";

				$res = $this->dbConnection->prepare($query);

				$res->bindParam(1, $user_id);
				$res->bindParam(2, $int_id);

				$res->execute();
			}
		}
		catch (PDOException $e)
        {
			// set error
            $this->dbConnection->SetError(_("Error inserting user interests")." - ".$e->getMessage());
			throw $e;
        }
	}

	public function user_interests_modify($data)
	{
		try
		{
			// delete interests
			$query = "DELETE FROM user_interests WHERE user_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $data['id']);

			$res->execute();

			// readd interests
			$this->user_interests_add($data, $data['id']);
		}
		catch (PDOException $e)
        {
			// set error
            $this->dbConnection->SetError(_("Error modifying user interests")." - ".$e->getMessage());
			throw $e;
        }
	}
}
?>
