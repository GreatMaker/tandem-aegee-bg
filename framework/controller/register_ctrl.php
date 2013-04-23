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
				$this->ret['error'] = _("Name error");
				return;
			}

			// check surname
			if (!isset($this->post_data['surname']) || $this->post_data['surname'] == "")
			{
				$this->ret['error'] = _("Surname error");
				return;
			}

			// check mail
			if (checkEmail($this->post_data['email']) == FALSE)
			{
				$this->ret['error'] = _("E-Mail error");
				return;
			}
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
