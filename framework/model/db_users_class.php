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
			$query = "INSERT INTO users (name, surname, username, email, birthdate, gender, facebook, about) VALUES (?, ?, ?, ?, STR_TO_DATE(?, '%d/%m/%Y'), ?, ?, ?)";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $data['name']);
			$res->bindParam(2, $data['surname']);
			$res->bindParam(3, $data['username']);
			$res->bindParam(4, $data['email']);
			$res->bindParam(5, $data['birthdate']);
			$res->bindParam(6, $data['sex']);
			$res->bindParam(7, $data['fb']);
			$res->bindParam(8, $data['about']);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error inserting new user")." - ".$e->getMessage());
			throw $e;
        }
	}

	public function user_modify($data)
	{
		try
        {
			$query = "UPDATE users SET email = ?, birthdate = STR_TO_DATE(?, '%d/%m/%Y'), gender = ?, facebook = ?, about = ? WHERE id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $data['email']);
			$res->bindParam(2, $data['birthdate']);
			$res->bindParam(3, $data['sex']);
			$res->bindParam(4, $data['fb']);
			$res->bindParam(5, $data['about']);
			$res->bindParam(6, $data['id']);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error modifying user"));
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
            $this->dbConnection->SetError(_("Error deleting user"));
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
				$cnt = $res->fetchColumn();

				if ($cnt > 0)
					return true;
			}

            return false;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user information"));
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
            $this->dbConnection->SetError(_("Error user information"));
        }
	}

	public function user_get_data($username, $is_md5 = false)
	{
		try
        {
			if ($is_md5 == false)
				$query = "SELECT id, username, password, name, surname, email, DATE_FORMAT(birthdate, '%d/%m/%Y') as birthdate, gender, facebook, about, active, invisible, admin, note FROM users WHERE username = ? LIMIT 1"; // TODO: la data la prende sbagliata
			else
				$query = "SELECT id, username, password, name, surname, email, DATE_FORMAT(birthdate, '%d/%m/%Y') as birthdate, gender, facebook, about, active, invisible, admin, note FROM users WHERE md5(username) = ? LIMIT 1";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $username);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_ASSOC);

			return $data[0];
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user information"));
        }
	}

	public function user_get_id_by_username($username, $is_md5 = false)
	{
		try
        {
			if ($is_md5 == false)
				$query = "SELECT id FROM users WHERE username = ? LIMIT 1";
			else
				$query = "SELECT id FROM users WHERE md5(username) = ? LIMIT 1";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $username);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_ASSOC);

			return $data[0]['id'];
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user information"));
        }
	}

	public function user_get_data_by_id($user_id)
	{
		try
        {
			$query = "SELECT *, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(birthdate)), '%Y')+0 AS age FROM users WHERE id = ? LIMIT 1";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_ASSOC);

			return $data[0];
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user information"));
        }
	}
	
	public function user_toggle_invisible($user_id)
	{
		try
        {
			$query = "UPDATE users SET invisible = !invisible WHERE id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);

			$res->execute();
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user set invisible"));
        }	
	}
}
?>
