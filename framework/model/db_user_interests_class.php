<?php
/*
 * User Interests table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

class user_interests_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
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
            $this->dbConnection->SetError(_("Error inserting user interests"));
			throw $e;
        }
	}
}
?>
