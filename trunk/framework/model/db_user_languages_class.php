<?php
/*
 * User languages table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
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
}
?>

