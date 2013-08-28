<?php
/*
 * Contents table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class contents_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function get_contents_page($page_name)
	{
		try
        {
			$query = "SELECT html FROM contents WHERE page_id = ? ORDER BY contents.pinned DESC, contents.order";

			$res = $this->dbConnection->prepare($query);

			$res->bindParam(1, $page_name);

			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_ASSOC);

			return $data;
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error retreiving page contents"));
        }
	}
}
?>
