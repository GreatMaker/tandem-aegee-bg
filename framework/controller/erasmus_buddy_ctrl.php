<?php
/*
 * Erasmus Buddy control class.
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

class erasmus_buddy_ctrl extends ctrl_abstract
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

		// check faculty
		if (!isset($this->post_data['faculty']) || $this->post_data['faculty'] == "")
			throw new Exception("Faculty field error");
		
		// check Languages
		if (!isset($this->post_data['languages']) || $this->post_data['languages'] == "")
			throw new Exception("Languages field error");

		// check mail
		if (checkEmail($this->post_data['email']) == FALSE)
			throw new Exception("E-Mail field error");

		return;
	}
	
	private function apply()
	{
		try
		{
			// send mail to new user
			$mail = new mailman_generic_class();
			$mail->set_object(_("Nuovo Erasmus Buddy"));
			$mail->set_receiver_mail("info@aegeebergamo.eu");
			$mail->set_data(array('name' => $this->post_data['name'], 'surname' => $this->post_data['surname'], 'email' => $this->post_data['email'], 'faculty' => $this->post_data['faculty'], 'languages' => $this->post_data['languages']));

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

	public function get_reply()
	{
		return $this->ret;
	}
}
?>
