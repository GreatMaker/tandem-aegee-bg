<?php
/*
 * Top Menu table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

class top_menu_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function get_top_menu()
	{
		try
        {
			$query = "SELECT name, page FROM top_menu ORDER BY top_menu.order";

			if ($res = $this->dbConnection->query($query))
			{
				$data = $res->fetchAll(PDO::FETCH_ASSOC);
				
				return $data;
			}
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error retreiving top menu"));
        }
	}
}
?>
