<?php
/*
 * Form class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

class form_field
{
	// fields
	const FIELD_TEXT		= "text";
	const FIELD_CHECKBOX	= "checkbox";
	const FIELD_RADIO		= "radio";
	const FIELD_OPTION		= "option";
	const FIELD_PASSWORD	= "password";
	const FIELD_TEXTAREA	= "textarea";
	const FIELD_BUTTON		= "button";
	const FIELD_LEGEND		= "legend";
	const FIELD_HIDDEN		= "hidden";

	private $field_name;
	private $field_label;
	private $field_type;
	private $field_readonly;
	private $field_value;
	private $field_data;
	private $field_style;

	public function __construct($name, $label, $readonly = false)
	{
		$this->field_name		= $name;
		$this->field_label		= $label;

		if ($readonly)
			$this->field_readonly	= "readonly";
	}

	public function set_type($type)
	{
		$this->field_type = $type;
	}
	
	public function set_name($name)
	{
		$this->field_name = $name;
	}
	
	public function set_label($label)
	{
		$this->field_label = $label;
	}
	
	public function set_value($value)
	{
		$this->field_value = $value;
	}
	
	public function set_data($data)
	{
		$this->field_data = $data;
	}

	public function set_style($style)
	{
		$this->field_style = $style;
	}

	public function get_type()
	{
		return $this->field_type;
	}

	public function get_name()
	{
		return $this->field_name;
	}

	public function get_label()
	{
		return $this->field_label;
	}

	public function get_readonly()
	{
		return $this->field_readonly;
	}

	public function get_value()
	{
		return $this->field_value;
	}
	
	public function get_data()
	{
		return $this->field_data;
	}

	public function get_style()
	{
		return $this->field_style;
	}
}

class form_class
{
	// method
	const METHOD_GET	= "get";
	const METHOD_POST	= "post";

	// type
	const TYPE_SIMPLE	= 0;
	const TYPE_FIELDSET	= 1;

	private $form_data = "";
	private $form_type;
	private $form_name;

	public function __construct($name, $action, $method, $type = self::TYPE_FIELDSET, $title = "")
	{
		if ($type == self::TYPE_FIELDSET)
			$this->form_data .= "<fieldset>";

		if (isset($title) && $title != "")
			$this->form_data .= "<legend>".$title."</legend>\n";

		$this->form_data .= "<form id=\"".$name."\" name=\"".$name."\" action=\"".$action."\" method=\"".$method."\">\n";		

		// set type
		$this->form_type = $type;

		// set name
		$this->form_name = $name;
	}

	public function add($field)
	{
		$type =		$field->get_type();
		$name =		$field->get_name();
		$value =	$field->get_value();
		$label =	$field->get_label();
		$data =		$field->get_data();
		$readonly =	$field->get_readonly();
		$style =	$field->get_style();

		if ($type != form_field::FIELD_BUTTON)
			$this->form_data .= "<p>";
			 
		if (isset($label) && ($label != ""))
		   $this->form_data .= "<label for=\"".$name."\">".$label."</label>\n";

		if ($type == form_field::FIELD_TEXT)
		{
			$this->form_data .= "<input name=\"".$name."\" id=\"".$name."\" value=\"".$value."\" type=\"text\" style=\"".$style."\" ".$readonly."/></p>\n";
		}
		else if ($type == form_field::FIELD_PASSWORD)
		{
			$this->form_data .= "<input name=\"".$name."\" id=\"".$name."\" value=\"".$value."\" type=\"password\" style=\"".$style."\" /></p>\n";
		}
		else if ($type == form_field::FIELD_CHECKBOX)
		{
			$this->form_data .= "<input name=\"".$name."\" id=\"".$name."\" value=\"".$value."\" type=\"checkbox\" style=\"".$style."\" /></p>\n";
		}
		else if ($type == form_field::FIELD_RADIO)
		{
			foreach ($data as $r_value => $r_text)
			{
				$this->form_data .= "<input name=\"".$name."\" id=\"".$name."\" value=\"".$r_value."\" type=\"radio\" style=\"".$style."\" /> ".$r_text;
				$this->form_data .= "<span style=\"padding: 0 10px\">&nbsp;</span>";
			}

			$this->form_data .= "</p>\n";
		}
		else if ($type == form_field::FIELD_TEXTAREA)
		{
			$this->form_data .= "<textarea cols=\"45\" rows=\"11\" name=\"".$name."\" id=\"".$name."\" style=\"".$style."\" >".$value."</textarea></p>\n";
		}
		else if ($type == form_field::FIELD_BUTTON)
		{
			$this->form_data .= "<input name=\"".$name."\" style=\"margin-left: 0px;\" class=\"formbutton\" value=\"".$value."\" type=\"submit\" style=\"".$style."\" />";
		}
		else if ($type == form_field::FIELD_LEGEND)
		{
			$this->form_data .= "<legend>".$name."</legend>\n";
		}
		else if ($type == form_field::FIELD_HIDDEN)
		{
			$this->form_data .= "<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\">\n";
		}
	}

	public function get_form()
	{
		// insert hidden form name info
		$this->form_data .= "\n<input type=\"hidden\" name=\"hidden_form_name\" value=\"".$this->form_name."\">";

		$this->form_data .= "</form>\n";

		if ($this->form_type == self::TYPE_FIELDSET)
			$this->form_data .= "\n</fieldset>";

		// return data
		return $this->form_data;
	}
}
?>