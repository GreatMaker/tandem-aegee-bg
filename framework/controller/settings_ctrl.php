<?php
/*
 * Settings control class.
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

require_once 'controller_interface.php';
require_once '../cookie_class.php';
require_once '../utils.php';

class settings_ctrl extends ctrl_abstract
{
	private $ret;
	private $post_data;
	private $dbConnection;
	private $cookie;

	public function __construct($data)
	{
		// save post data
		$this->post_data = $data;

		// ret array
		$this->ret = array();
		
		// Cookie
		$this->cookie = new cookie_class();
	}
	
	public function process()
	{
		try
		{
			// validate form data
			$this->validate();

			// use data
			$this->apply();
		}
		catch (Exception $e)
		{
			$this->ret['error'] = $e->getMessage();
			return;
		}
		catch (PDOException $e)
		{
			$this->ret['error'] = $e->getMessage();
			return;
		}

		// redirect to home
		$this->ret['redirect'] = "index.php?page=home";
	}

	private function validate()
	{
		/*
		// check name
		if (!isset($this->post_data['name']) || $this->post_data['name'] == "")
			throw new Exception("Name field error");

		// check surname
		if (!isset($this->post_data['surname']) || $this->post_data['surname'] == "")
			throw new Exception("Surname field error");*/

		// check sex
		if (!isset($this->post_data['sex']) || $this->post_data['sex'] == "")
			throw new Exception("Sex field error");

		// check birthdate
		if (!isset($this->post_data['birthdate']) || $this->post_data['birthdate'] == "")
			throw new Exception("Birthdate field error");

		// check mail
		if (checkEmail($this->post_data['email']) == FALSE)
			throw new Exception("E-Mail field error");

		// fix facebook
		if (isset($this->post_data['fb']) && $this->post_data['fb'] != "")
		{
			fix_facebook_link($this->post_data['fb']);
		}
		
		// if manual user, check if change password is requeste and if check is ok
		if (isset($this->post_data['new_password']) && $this->post_data['new_password'] != "")
		{
			if ($this->post_data['new_password'] != $this->post_data['new_password_conf'])
				throw new Exception("Password check failed");
		}

		// check spoken langs
		$values_spoken = array();

		foreach ($this->post_data['p_lang_speak'] as $id => $data)
		{
			if ($data['lang_speak'] == "-1")
				throw new Exception("Select a valid language you speak");

			// check unique
			if (in_array($data['lang_speak'], $values_spoken))
				throw new Exception("Duplicate entry for spoken languages");

			$values_spoken[] = $data['lang_speak'];
		}
		
		// check spoken langs
		$values_learn = array();

		foreach ($this->post_data['p_lang_learn'] as $id => $data)
		{
			if ($data['lang_learn'] == "-1")
				throw new Exception("Select a valid language you want to learn");

			// check unique
			if (in_array($data['lang_learn'], $values_learn))
				throw new Exception("Duplicate entry for learning languages");

			$values_learn[] = $data['lang_learn'];
		}

		// check both mother tongue and learn languages
		$res = array_intersect($values_spoken, $values_learn);

		if (count($res) > 0)
		{
			throw new Exception("You cannot set a language as native and to learn as well!");
		}

		return;
	}
	
	private function apply()
	{
		if ($this->getDBConnection($this->dbConnection) == TRUE)
		{
			try
			{
				// start transaction
				//$this->dbConnection->beginTransaction();

				// modify user user
				$this->dbConnection->user_modify($this->post_data);

				// add languages
				$this->dbConnection->user_languages_modify($this->post_data);

				// add interests
				$this->dbConnection->user_interests_modify($this->post_data);

				// commit data
				//$this->dbConnection->commit();
			}
			catch (Exception $e)
			{
				// roll back data
				//$this->dbConnection->rollBack();

				// throw exception
				throw new Exception(_("Error modifying user").$e->getMessage());
			}
			catch (PDOException $e)
			{
				// roll back data
				//$this->dbConnection->rollBack();

				$err_str = "";

				if ($this->dbConnection->GetError($err_str) == true)
					throw new Exception($err_str);
			}
		}
		else
			throw new Exception(_("Database connection error"));
	}

	public function get_reply()
	{
		return $this->ret;
	}
}
?>
