<?php
/*
 * Form class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class form_field
{
	// fields
	const FIELD_TEXT		= "text";
	const FIELD_CHECKBOX	= "checkbox";
	const FIELD_CHECKGRID	= "checkgrid";
	const FIELD_RADIO		= "radio";
	const FIELD_OPTION		= "option";
	const FIELD_PASSWORD	= "password";
	const FIELD_TEXTAREA	= "textarea";
	const FIELD_BUTTON		= "button";
	const FIELD_LEGEND		= "legend";
	const FIELD_NOTE		= "note";
	const FIELD_HIDDEN		= "hidden";

	private $field_name;
	private $field_label;
	private $field_type;
	private $field_readonly;
	private $field_value;
	private $field_data;
	private $field_style;
	private $field_extra;

	public function __construct($name, $label = "", $readonly = false)
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

	public function set_extra($extra)
	{
		$this->field_extra = $extra;
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

	public function get_extra()
	{
		return $this->field_extra;
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

	public function add($field, $paragraph = true, $paragraph_name = false)
	{
		$type =		$field->get_type();
		$name =		$field->get_name();
		$value =	$field->get_value();
		$label =	$field->get_label();
		$data =		$field->get_data();
		$readonly =	$field->get_readonly();
		$style =	$field->get_style();
		$extra =	$field->get_extra();

		if ($type != form_field::FIELD_BUTTON && $paragraph == true)
		{
			$this->form_data .= "<p id=\"p_".$name."\"";
			
			if ($paragraph_name == true)
				$this->form_data .= " name=\"p_".$name."\"";
				
			$this->form_data .= ">";
		}

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
		else if ($type == form_field::FIELD_CHECKGRID)
		{
			$this->form_data .= "<div style=\"float: right; width: 500px;\"><ul class=\"checkbox-grid\">\n";

			foreach ($data as $id => $int_data)
			{						
				$this->form_data .= "<li><input name=\"".$name."[]\" value=\"".$int_data['id']."\" type=\"checkbox\" style=\"".$style."\" ";

				if (in_array($int_data['id'], $value))
					$this->form_data .= " checked=\"checked\"";
		
				$this->form_data .= "/>&nbsp;".$int_data['interest']."</li>\n";
			}

			$this->form_data .= "</ul></div><br />";
			
			if ($paragraph == true)
				$this->form_data .= "</p>\n";
		}
		else if ($type == form_field::FIELD_RADIO)
		{
			foreach ($data as $r_value => $r_text)
			{
				$this->form_data .= "<input name=\"".$name."\" id=\"".$name."\" value=\"".$r_value."\" type=\"radio\" style=\"".$style."\"";

				if ($value == $r_value)
					$this->form_data .= " checked=\"checked\"";

				$this->form_data .= "/> ".$r_text;
				$this->form_data .= "<span style=\"padding: 0 10px\">&nbsp;</span>";
			}

			if ($paragraph == true)
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
		else if ($type == form_field::FIELD_OPTION)
		{
			$this->form_data .= "<select name=\"".$name."\" id=\"".$name."\" style=\"".$style."\">\n";

			foreach ($data as $r_value => $r_text)
			{
				$this->form_data .= "<option value=\"".$r_value."\">".$r_text."</option>\n";
			}

			$this->form_data .= "</select>";

			if (isset($extra) && $extra != "")
				$this->form_data .= $extra;

			if ($paragraph == true)
				$this->form_data .= "</p>";
		}
		else if ($type == form_field::FIELD_NOTE)
		{
			$this->form_data .= $value;
		}
	}

	public function fieldset_open($title, $id)
	{
		$this->form_data .= "<fieldset id=\"".$id."\" name=\"".$id."\">";

		if (isset($title) && $title != "")
			$this->form_data .= "<legend>".$title."</legend><br/>\n";
	}

	public function fieldset_close()
	{
		$this->form_data .= "</fieldset>\n";
	}

	public function div_open($id)
	{
		$this->form_data .= "<div id=\"".$id."\">";
	}
	
	public function div_close()
	{
		$this->form_data .= "</div>\n";
	}
	
	public function paragraph_open($id)
	{
		$this->form_data .= "<p id=\"".$id."\" name=\"".$id."\" class=\"".$id."\">";
	}
	
	public function paragraph_close()
	{
		$this->form_data .= "</p>\n";
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
