<?php
/*
 * Languages table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
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