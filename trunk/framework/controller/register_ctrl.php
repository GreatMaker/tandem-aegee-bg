<?php
/*
 * Register control class.
 */

require_once 'controller_interface.php';
require_once '../utils.php';

class register_ctrl implements ctrl_interface
{
	private $ret;
	private $post_data;

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
			// check name
			if (!isset($this->post_data['name']) || $this->post_data['name'] == "")
			{
				$this->ret['error'] = _("Name field error");
				return;
			}

			// check surname
			if (!isset($this->post_data['surname']) || $this->post_data['surname'] == "")
			{
				$this->ret['error'] = _("Surname field error");
				return;
			}

			// check sex
			if (!isset($this->post_data['sex']) || $this->post_data['sex'] == "")
			{
				$this->ret['error'] = _("Sex field error");
				return;
			}
			
			// check birthdate
			if (!isset($this->post_data['birthdate']) || $this->post_data['birthdate'] == "")
			{
				$this->ret['error'] = _("Birthdate field error");
				return;
			}

			// check mail
			if (checkEmail($this->post_data['email']) == FALSE)
			{
				$this->ret['error'] = _("E-Mail field error");
				return;
			}


				$this->ret['error'] = print_r($_POST, true);
				return;

		}
		catch (Exception $e)
		{
			$this->ret['error'] = $e;
			return;
		}

		// redirect to home
		$this->ret['redirect'] = "index.php?page=home";
	}

	public function get_reply()
	{
		return $this->ret;
	}
}
?>
