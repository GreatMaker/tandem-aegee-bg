<?php
/*
 * Social Toolbar table class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class social_toolbar_table
{
    private $dbConnection;

    public function __construct(&$dbconn)
    {
        $this->dbConnection = $dbconn;
    }

	public function get_social_toolbar()
	{
		try
        {
			$query = "SELECT socials.name_ext, socials.icon, socials.link as link_1, social_toolbar.link as link_2 FROM social_toolbar JOIN socials ON socials.name = social_toolbar.social ORDER BY social_toolbar.order";

			if ($res = $this->dbConnection->query($query))
			{
				$data = $res->fetchAll(PDO::FETCH_ASSOC);
				
				return $data;
			}
		}
		catch (PDOException $e)
        {
            $this->dbConnection->SetError(_("Error retreiving toolbar"));
        }
	}
}
?>
