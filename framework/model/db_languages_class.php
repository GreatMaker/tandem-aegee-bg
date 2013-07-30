<?php
/*
 * Languages table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class languages_table
{
    private $dbConnection;
	private $lang_levels = array(0 => "Beginner", 1 => "Intermediate", 2 => "Advanced");

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function languages_get_levels()
	{
		return $this->lang_levels;
	}

	public function languages_get_level($level)
	{
		return $this->lang_levels[$level];
	}

	public function languages_get_lang_name($lang_code)
	{
		try
        {
			$query = "SELECT lang_name FROM languages WHERE lang_code = ? LIMIT 1";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $lang_code);

			$res->execute();

			$row = $res->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);

			return $row['lang_name'];
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error language code"));
        }
	}

	public function languages_get_data()
	{
		try
        {
			$data = array();

			$query = "SELECT lang_code, lang_name FROM languages ORDER BY id";

			$res = $this->dbConnection->prepare($query);

			$res->execute();

			// insert null entry
			$data[-1] = _("- Select language -");

			while ($row = $res->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
			{
				$data[$row['lang_code']] = $row['lang_name'];
			}

			return $data;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error languages information"));
        }
	}
}
?>