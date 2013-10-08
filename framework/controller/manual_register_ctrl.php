<?php
/*
 * Register control class.
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

require_once 'controller_interface.php';
require_once '../utils.php';
require_once '../mail_class.php';

class manual_register_ctrl extends ctrl_abstract
{
	private $ret;
	private $post_data;
	private $dbConnection;

	public function __construct($data)
	{
		// save post data
		$this->post_data = $data;

		// ret array
		$this->ret = array();
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

		// redirect to home
		$this->ret['reload'] = "true";
	}

	private function validate()
	{
		// check name
		if (!isset($this->post_data['name']) || $this->post_data['name'] == "")
			throw new Exception("Name field error");

		// check surname
		if (!isset($this->post_data['surname']) || $this->post_data['surname'] == "")
			throw new Exception("Surname field error");

		// check username
		if (!isset($this->post_data['username']) || $this->post_data['username'] == "")
			throw new Exception("Username field error");

		// check sex
		if (!isset($this->post_data['sex']) || $this->post_data['sex'] == "")
			throw new Exception("Sex field error");

		// check birthdate
		if (!isset($this->post_data['birthdate']) || $this->post_data['birthdate'] == "")
			throw new Exception("Birthdate field error");

		// check mail
		if (checkEmail($this->post_data['email']) == FALSE)
			throw new Exception("E-Mail field error");

		return;
	}
	
	private function apply()
	{
		if ($this->getDBConnection($this->dbConnection) == TRUE)
		{
			try
			{
				// generate random password string
				$pwd = generateRandomString(6);

				// add new user
				$this->dbConnection->user_manual_add($this->post_data, $pwd);

				// send mail to new user
				$mail = new mailman_reg_class();
				$mail->set_object(_("Welcome to the Tandem Project"));
				$mail->set_receiver($this->post_data['name']);
				$mail->set_receiver_mail($this->post_data['email']);
				$mail->set_data(array('username' => $this->post_data['username'], 'password' => $pwd));

				// Send mail
				$mail->send_message();
			}
			catch (PDOException $e)
			{
				$err_str = "";

				if ($this->dbConnection->GetError($err_str))
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
