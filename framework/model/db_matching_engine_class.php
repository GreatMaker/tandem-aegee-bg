<?php
/*
 * Buddies matching engine database class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

define("SCORE_TUTOR", 50);
define("SCORE_NOT_TUTOR", 10);
define("SCORE_INTEREST", 5);

class matching_engine
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function matching_find_buddies($user_id)
	{
		try
        {
			$query = "SELECT users.id, user_languages.lang_code, user_languages.mother_tongue, user_languages.level FROM users JOIN user_languages ON users.id = user_languages.user_id 
					  WHERE users.id IN (SELECT b.id FROM (SELECT @user_id:=".$user_id.") a, users_available b 
					  JOIN user_languages ON b.id = user_languages.user_id 
					  WHERE user_languages.lang_code IN (SELECT lang_code FROM user_speak_languages))
					  AND user_languages.lang_code IN (SELECT lang_code FROM user_learn_languages) AND  (user_languages.mother_tongue = 1 OR (user_languages.mother_tongue = 0 AND user_languages.level > (SELECT level FROM user_learn_languages WHERE lang_code = user_languages.lang_code)))
					  ORDER BY user_languages.lang_code, user_languages.mother_tongue DESC, user_languages.level DESC";

			$query = "SELECT users.id, user_languages.lang_code, user_languages.mother_tongue, user_languages.level FROM (SELECT @user_id:=".$user_id.") u, users JOIN user_languages ON users.id = user_languages.user_id 
					  WHERE user_languages.mother_tongue = 1 AND user_languages.lang_code IN (SELECT lang_code FROM user_learn_languages) AND users.id IN (SELECT b.id FROM (SELECT @user_id:=".$user_id.") a, users_available b 
JOIN user_languages ON b.id = user_languages.user_id 
WHERE (user_languages.lang_code IN (SELECT lang_code FROM user_learn_languages) AND user_languages.mother_tongue = 1)
AND b.id IN (
SELECT b.id FROM (SELECT @user_id:=".$user_id.") a, users_available b 
JOIN user_languages ON b.id = user_languages.user_id 
WHERE (user_languages.lang_code IN (SELECT lang_code FROM user_speak_languages) AND user_languages.mother_tongue = 0)
))
						ORDER BY user_languages.lang_code, user_languages.mother_tongue DESC, user_languages.level DESC";
			
			$query = "SELECT users.id, user_languages.lang_code, user_languages.mother_tongue, user_languages.level FROM (SELECT @user_id:=".$user_id.") u, users JOIN user_languages ON users.id = user_languages.user_id 
					  WHERE user_languages.mother_tongue = 1 AND user_languages.lang_code IN (SELECT lang_code FROM user_learn_languages) AND users.id IN (SELECT b.id FROM users_available b 
					  JOIN user_languages ON b.id = user_languages.user_id 
					  WHERE (user_languages.lang_code IN (SELECT lang_code FROM user_speak_languages) AND user_languages.mother_tongue = 0) )
					  ORDER BY user_languages.lang_code, user_languages.mother_tongue DESC, user_languages.level DESC";
			
			if ($res = $this->dbConnection->query($query))
			{
				$data = $res->fetchAll(PDO::FETCH_ASSOC);

				return $this->matching_get_score($user_id, $data);
			}
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error retreiving interests"));
        }
	}

	private function matching_get_score($user_id, $buddy_data)
	{
		$score_data = array();

		try
        {
			foreach ($buddy_data as $id => $b_data)
			{
				$query = "SELECT COUNT(*) as nr_interests FROM user_interests WHERE user_id = ".$user_id." UNION ALL SELECT COUNT(*) as nr_interests FROM user_interests a JOIN user_interests b ON a.interest_id = b.interest_id WHERE b.user_id = ".$b_data['id']." AND a.user_id = ".$user_id;

				if ($res = $this->dbConnection->query($query))
				{
					$data = $res->fetchAll(PDO::FETCH_ASSOC);

					if (array_key_exists($b_data['lang_code'], $score_data))
					{
						// buddy id
						$score_data[$b_data['lang_code']][$b_data['id']] = array();
					}
					else
					{
						$score_data[$b_data['lang_code']] = array();

						// buddy id
						$score_data[$b_data['lang_code']][$b_data['id']] = array();
					}
					
					// lang score
					if ($b_data['mother_tongue'] == 1)
						$score_data[$b_data['lang_code']][$b_data['id']]['score'] = SCORE_TUTOR;
					else
						$score_data[$b_data['lang_code']][$b_data['id']]['score'] = SCORE_NOT_TUTOR * $b_data['level'];

					// affinity score
					$score_data[$b_data['lang_code']][$b_data['id']]['score'] += $data[1]['nr_interests'] * SCORE_INTEREST;

					// max score
					$score_data[$b_data['lang_code']][$b_data['id']]['max_score'] = SCORE_TUTOR + ($data[0]['nr_interests'] * SCORE_INTEREST);
				}
			}

			return $score_data;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error retreiving interests"));
        }
	}

	// TODO: implement single user get affinity details
	function matching_get_single_score($user_id, $buddy_id)
	{
		try
		{
			$query = "SELECT COUNT(*) as nr_interests FROM user_interests WHERE user_id = ".$user_id." UNION ALL SELECT COUNT(*) as nr_interests FROM user_interests a JOIN user_interests b ON a.interest_id = b.interest_id WHERE b.user_id = ".$buddy_id." AND a.user_id = ".$user_id;

			if ($res = $this->dbConnection->query($query))
			{
				$data = $res->fetchAll(PDO::FETCH_ASSOC);

				if (array_key_exists($b_data['lang_code'], $score_data))
				{
					// buddy id
					$score_data[$b_data['lang_code']][$b_data['id']] = array();
				}
				else
				{
					$score_data[$b_data['lang_code']] = array();

					// buddy id
					$score_data[$b_data['lang_code']][$b_data['id']] = array();
				}

				// lang score
				if ($b_data['mother_tongue'] == 1)
					$score_data[$b_data['lang_code']][$b_data['id']]['score'] = SCORE_TUTOR;
				else
					$score_data[$b_data['lang_code']][$b_data['id']]['score'] = SCORE_NOT_TUTOR * $b_data['level'];

				// affinity score
				$score_data[$b_data['lang_code']][$b_data['id']]['score'] += $data[1]['nr_interests'] * SCORE_INTEREST;

				// max score
				$score_data[$b_data['lang_code']][$b_data['id']]['max_score'] = SCORE_TUTOR + ($data[0]['nr_interests'] * SCORE_INTEREST);
			}
			
			return $score_data;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error retreiving matching data"));
        }
	}

	public function matching_get_common_interests($user_id, $buddy_id)
	{
		try
		{
			$query = "SELECT c.interest FROM user_interests a JOIN user_interests b ON a.interest_id = b.interest_id JOIN interests c ON b.interest_id = c.id WHERE b.user_id = ".$buddy_id." AND a.user_id = ".$user_id;

			if ($res = $this->dbConnection->query($query))
			{
				$data = $res->fetchAll(PDO::FETCH_ASSOC);

				return $data;
			}
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error retreiving interests"));
        }
	}
}

// SELECT a.* FROM (SELECT @user_id :=4)p, users_available a

// SELECT b. * FROM (SELECT @user_id:=14) a, users_available b JOIN user_languages ON b.id = user_languages.user_id WHERE user_languages.lang_code IN (SELECT lang_code FROM user_learn_languages) AND user_languages.mother_tongue = 1
// SELECT b. * FROM (SELECT @user_id:=14) a, users_available b JOIN user_languages ON b.id = user_languages.user_id WHERE user_languages.lang_code IN (SELECT lang_code FROM user_learn_languages) AND  (user_languages.mother_tongue = 1 OR (user_languages.mother_tongue = 0 AND user_languages.level > (SELECT level FROM user_learn_languages WHERE lang_code = user_languages.lang_code)))

// SELECT b.username, user_languages.lang_code, user_languages.mother_tongue, user_languages.level FROM (SELECT @user_id:=14) a, users_available b JOIN user_languages ON b.id = user_languages.user_id WHERE user_languages.lang_code IN (SELECT lang_code FROM user_learn_languages) AND  (user_languages.mother_tongue = 1 OR (user_languages.mother_tongue = 0 AND user_languages.level > (SELECT level FROM user_learn_languages WHERE lang_code = user_languages.lang_code)))


// SELECT * FROM users JOIN user_languages ON users.id = user_languages.user_id WHERE users.id IN (SELECT b.id 
// FROM 
// (SELECT @user_id:=14) a, users_available b 
// JOIN user_languages ON b.id = user_languages.user_id 
// WHERE user_languages.lang_code IN (SELECT lang_code FROM user_learn_languages) AND user_languages.mother_tongue = 1)
// AND user_languages.lang_code IN (SELECT lang_code FROM user_speak_languages)



// SELECT * FROM users JOIN user_languages ON users.id = user_languages.user_id WHERE users.id IN (SELECT b.id 
// FROM 
// (SELECT @user_id:=14) a, users_available b 
// JOIN user_languages ON b.id = user_languages.user_id 
// WHERE user_languages.lang_code IN (SELECT lang_code FROM user_learn_languages) AND  (user_languages.mother_tongue = 1 OR (user_languages.mother_tongue = 0 AND user_languages.level > (SELECT level FROM user_learn_languages WHERE lang_code = user_languages.lang_code))))
// AND user_languages.lang_code IN (SELECT lang_code FROM user_speak_languages)

// SELECT COUNT(*) as nr_interests FROM user_interests WHERE user_id = 14 UNION ALL SELECT COUNT(*) as nr_interests FROM user_interests a JOIN user_interests b ON a.interest_id = b.interest_id WHERE b.user_id <> 14 AND a.user_id <> b.user_id
?>
