<?php
/*
 * User languages table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class user_languages_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function user_languages_add($data, $user_id)
	{
		try
		{
			foreach ($data['p_lang_speak'] as $id => $lang_data)
			{
				$query = "INSERT INTO user_languages (user_id, lang_code, mother_tongue) VALUES (?, ?, 1)";

				$res = $this->dbConnection->prepare($query);

				$res->bindParam(1, $user_id);
				$res->bindParam(2, $lang_data['lang_speak']);

				$res->execute();
			}

			foreach ($data['p_lang_learn'] as $id => $lang_data)
			{
				$query = "INSERT INTO user_languages (user_id, lang_code, mother_tongue, level) VALUES (?, ?, 0, ?)";

				$res = $this->dbConnection->prepare($query);

				$res->bindParam(1, $user_id);
				$res->bindParam(2, $lang_data['lang_learn']);
				$res->bindParam(3, $lang_data['lang_learn_level']);

				$res->execute();
			}
		}
		catch (PDOException $e)
        {
			// set error
            $this->dbConnection->SetError(_("Error inserting user languages"));
			throw $e;
        }
	}

	public function user_languages_modify($data, $user_id)
	{
		try
		{
			// delete interests
			$query = "DELETE FROM user_languages WHERE user_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $data['id']);

			$res->execute();

			// readd interests
			$this->user_languages_add($data, $data['id']);
		}
		catch (PDOException $e)
        {
			// set error
            $this->dbConnection->SetError(_("Error modifying user languages"));
			throw $e;
        }
	}

	public function user_languages_get_by_id($user_id)
	{
		try
        {
			$query = "SELECT user_languages.mother_tongue, user_languages.level, languages.lang_name FROM user_languages JOIN languages ON user_languages.lang_code = languages.lang_code WHERE user_languages.user_id = ? ORDER BY user_languages.mother_tongue DESC, user_languages.level";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_ASSOC);

			return $data;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user languages information"));
        }
	}

	public function user_spoken_languages_get_by_id($user_id)
	{
		try
        {
			$query = "SELECT lang_code FROM user_languages WHERE user_languages.mother_tongue = 1 AND user_languages.user_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_ASSOC);

			return $data;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user languages information"));
        }
	}

	public function user_learn_languages_get_by_id($user_id)
	{
		try
        {
			$query = "SELECT lang_code, level FROM user_languages WHERE user_languages.mother_tongue = 0 AND user_languages.user_id = ?";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $user_id);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_ASSOC);

			return $data;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error user languages information"));
        }
	}
}
?>

