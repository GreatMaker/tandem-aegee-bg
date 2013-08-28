<?php
/*
 * Pages table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class pages_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function get_pages_top_menu()
	{
		try
        {
			$query = "SELECT name, page FROM pages WHERE pages.top_menu = 1 ORDER BY pages.order";

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
