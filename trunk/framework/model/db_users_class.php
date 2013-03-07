<?php
/*
 * Users table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

class users_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function user_add($data)
	{
		try
        {
			$query = "INSERT INTO users (username, email, born_date, gender, facebook) VALUES (?, ?, ?, ?, ?)";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $data['username']);
			$res->bindParam(2, $data['email']);
			$res->bindParam(3, $data['born_date']);
			$res->bindParam(4, $data['gender']);
			$res->bindParam(5, $data['facebook']);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Errore inserimento nuovo utente"));
        }
	}

	public function user_update($data)
	{
		try
        {
			$query = "UPDATE users SET email = ?, born_date = ?, gender = ?, facebook = ? WHERE id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $data['email']);
			$res->bindParam(2, $data['born_date']);
			$res->bindParam(3, $data['gender']);
			$res->bindParam(4, $data['facebook']);
			$res->bindParam(5, $data['hidden_user_id']);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Errore modifica utente"));
        }
	}

	public function user_delete($data)
	{
		try
        {
			$query = "DELETE FROM users WHERE id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $data['hidden_user_id']);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Errore cancellazione utente"));
        }
	}

	public function user_is_registered($username)
	{
		try
        {
			$query = "SELECT COUNT(*) FROM users WHERE username = '".$username."'";

			if ($res = $this->dbConnection->query($query))
			{
				/* Check the number of rows that match the SELECT statement */
				if ($res->fetchColumn() > 0)
					return true;
			}

            return false;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Errore informazioni utente"));
        }
	}

	public function user_auth($username, $password)
	{
		try
        {
			$query = "SELECT * FROM users WHERE username = ? AND password = md5(?) LIMIT 1";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $username);
			$res->bindParam(2, $password);
			$res->execute();

			if ($res->fetchColumn() > 0)
				return true;

			return false;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Errore informazioni utente"));
        }
	}

	public function user_get_data($username, $is_md5 = false)
	{
		try
        {
			if ($is_md5 == false)
				$query = "SELECT * FROM users WHERE username = ? LIMIT 1";
			else
				$query = "SELECT * FROM users WHERE md5(username) = ? LIMIT 1";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $username);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_ASSOC);

			return $data[0];
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Errore informazioni utente"));
        }
	}
}
?>
