<?php
/*
 * Mail sender Class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class mailman_reg_class
{
	private $object;
	private $message;
	private $receiver_mail;
	private $data;
	private $receiver;

	public function __construct()
    {
		$this->message = "";
	}

	public function set_object($obj)
	{
		$this->object = $obj;
	}

	public function set_receiver($recv)
	{
		$this->receiver = $recv;
	}

	public function set_receiver_mail($recv_mail)
	{
		$this->receiver_mail = $recv_mail;
	}

	public function set_data($data)
	{
		$this->data = $data;
	}

	public function send_message()
	{
		$this->message  = "<html><body style=\"background-color: #E6E6E6; color: #666666; font-family: Verdana,Geneva,sans-serif; font-size: 13px;\">
		<img src=\"http://tandem.unibg.it/img/logo_tandem.png\" />
		<div style=\"width: 600px; padding: 10px; background-color: #FFFFFF; border-color: #CCCCCC #C0C0C0 #C0C0C0 #CCCCCC; border-image: none; border-style: solid; border-width: 1px;\">
			<div style=\"clear: both; overflow:auto;\">
				<div style=\"clear: both; font-size: 15px; margin-left: 10px;\">
					Hi ".$this->receiver.",<br/>
					welcome to the Tandem Project Bergamo!<br/><br/>
					Please login into the Tandem website using the following details and under settings fill your personal data, then find your buddy!<br/><br/>
					<strong>Username:</strong>&nbsp;".$this->data['username']."<br/>
					<strong>Password:</strong>&nbsp;".$this->data['password']."<br/><br/>
				</div>
			</div>
			<div style=\"clear: both; margin-right: 10px; overflow:auto;\">
			<a href=\"http://tandem.unibg.it/index.php?page=home\">
			<div style=\"float: right; border: 1px solid black; padding: 5px;  margin: 5px;\">Login</div>
			</a>
			</div>
		</div>
		<br/>
	</body>
</html>";

		$headers  = "From: Tandem Project Bergamo <tandem@tandem.unibg.it>" . "\r\n";
		$headers .= "Reply-To: no-reply@tandem.unibg.it" . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		mail($this->receiver_mail, $this->object, $this->message, $headers);
	}
}

class mailman_class
{
	private $object;
	private $message;
	private $receiver;
	private $receiver_mail;
	private $sender;
	private $sender_id;
	private $user_message;
	private $user_image;

	public function __construct()
    {
		$this->message = "";
	}

	public function set_object($obj)
	{
		$this->object = $obj;
	}

	public function set_receiver($recv)
	{
		$this->receiver = $recv;
	}

	public function set_sender($sender)
	{
		$this->sender = $sender;
	}

	public function set_sender_id($id)
	{
		$this->sender_id = $id;
	}

	public function set_receiver_mail($recv_mail)
	{
		$this->receiver_mail = $recv_mail;
	}

	public function set_user_message($msg)
	{
		$this->user_message = $msg;
	}

	public function set_user_image($img)
	{
		$this->user_image = $img;
	}

	public function send_message()
	{
		$this->message  = "<html><body style=\"background-color: #E6E6E6; color: #666666; font-family: Verdana,Geneva,sans-serif; font-size: 13px;\">
		<img src=\"http://tandem.unibg.it/img/logo_tandem.png\" />
		<div style=\"width: 600px; padding: 10px; background-color: #FFFFFF; border-color: #CCCCCC #C0C0C0 #C0C0C0 #CCCCCC; border-image: none; border-style: solid; border-width: 1px;\">
			<div style=\"clear: both; overflow:auto;\">
				<div style=\"clear: both; font-size: 15px; margin-left: 10px;\">
					Hi ".$this->receiver.",<br/>
					".$this->sender." just sent you a message, here it is:<br/><br/>
					<strong>".$this->sender."</strong>
				</div>
				<div style=\"border: 1px solid black; float: left; width: 50px; height: 50px; margin: 10px;\">
					<img src=\"http://graph.facebook.com/".$this->user_image."/picture\">
				</div>
				<div style=\"overflow:auto; margin-top: 10px;\">
				".$this->user_message."
				</div>
			</div>
			<div style=\"clear: both; margin-right: 10px; overflow:auto;\">
			<a href=\"http://tandem.unibg.it/index.php?page=profile&id=".$this->sender_id."\">
			<div style=\"float: right; border: 1px solid black; padding: 5px; margin: 5px;\">Profile</div>
			</a>
			<a href=\"http://tandem.unibg.it/index.php?page=home&option=message&to=".$this->sender_id."\">
			<div style=\"float: right; border: 1px solid black; padding: 5px;  margin: 5px;\">Reply</div>
			</a>
			</div>
		</div>
		<br/>
	</body>
</html>";

		$headers  = "From: Tandem Project Bergamo <tandem@tandem.unibg.it>" . "\r\n";
		$headers .= "Reply-To: no-reply@tandem.unibg.it" . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		mail($this->receiver_mail, $this->object, $this->message, $headers);
	}
}

class mailman_generic_class
{
	private $object;
	private $message;
	private $receiver_mail;
	private $data;

	public function __construct()
    {
		$this->message = "";
	}

	public function set_data($data)
	{
		$this->data = $data;
	}

	public function set_object($obj)
	{
		$this->object = $obj;
	}

	public function set_receiver_mail($recv_mail)
	{
		$this->receiver_mail = $recv_mail;
	}

	public function set_message($msg)
	{
		$this->message = $msg;
	}

	public function send_message()
	{
		$headers  = "From: Tandem Project Bergamo <tandem@tandem.unibg.it>" . "\r\n";
		$headers .= "Reply-To: no-reply@tandem.unibg.it" . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$this->message  = "
		<html>
		<head></head>
		<body>
		  <table>
		    <tr>
			  <td>Nome</td>
			  <td>Cognome</td>
			  <td>Email</td>
			  <td>Facoltà</td>
			  <td>Lingue</td>
		    </tr>
			<tr>
			  <td>".$this->data['name']."</td>
			  <td>".$this->data['surname']."</td>
			  <td>".$this->data['email']."</td>
			  <td>".$this->data['faculty']."</td>
			  <td>".$this->data['languages']."</td>
		    </tr>
		  </table>
		</body>
		</html>";

		mail($this->receiver_mail, $this->object, $this->message, $headers);
	}
}
?>
