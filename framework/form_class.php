<?php
/*
 * Form class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

class form_class
{
	// method
	const METHOD_GET	= "get";
	const METHOD_POST	= "post";

	// type
	const TYPE_SIMPLE	= 0;
	const TYPE_FIELDSET	= 1;

	// fields
	const FIELD_TEXT		= "text";
	const FIELD_CHECKBOX	= "checkbox";
	const FIELD_OPTION		= "option";
	const FIELD_PASSWORD	= "password";
	const FIELD_BUTTON		= "button";

	private $form_data = "";
	private $form_type;
	private $form_name;

	public function __construct($name, $action, $method, $type = self::TYPE_FIELDSET, $title = "")
	{
		if ($type == self::TYPE_FIELDSET)
			$this->form_data .= "<fieldset><legend>".$title."</legend>\n";

		$this->form_data .= "<fieldset><form id=\"".$name."\" name=\"".$name."\" action=\"".$action."\" method=\"".$method."\">\n";

		// set type
		$this->form_type = $type;

		// set name
		$this->form_name = $name;
	}

	public function add_field($type, $name, $label = "")
	{
		if ($type != self::FIELD_BUTTON)
			$this->form_data .= "<p>";
			 
		if (isset($label) && ($label != ""))
		   $this->form_data .= "<label for=\"".$name."\">".$label."</label>\n";

		if ($type == self::FIELD_TEXT)
		{
			$this->form_data .= "<input name=\"".$name."\" id=\"".$name."\" value=\"\" type=\"text\" /></p>\n";
		}
		else if ($type == self::FIELD_PASSWORD)
		{
			$this->form_data .= "<input name=\"".$name."\" id=\"".$name."\" value=\"\" type=\"text\" /></p>\n";
		}
		else if ($type == self::FIELD_BUTTON)
		{
			$this->form_data .= "<input name=\"".$name."\" style=\"margin-left: 0px;\" class=\"formbutton\" value=\"Send\" type=\"submit\" />";
		}
	}

	public function get_form()
	{
		// insert hidden form name info
		$this->form_data .= "\n<input type=\"hidden\" name=\"hidden_form_name\" value=\"".$this->form_name."\">";

		if ($this->form_type == self::TYPE_FIELDSET)
			$this->form_data .= "\n</fieldset>";

		$this->form_data .= "</fieldset></form>\n";

		// return data
		return $this->form_data;
	}
}
?>
