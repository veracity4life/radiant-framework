<?php

/**
*
*/
class View
{
	function __construct() {

	}

	public function render($page, $included = true)
	{
		if ($included == false) {
			require BASE_DIR . APP_DIR . '/views/' . $page . '.php';
		} else {
			require BASE_DIR . APP_DIR . '/views/header.php';
			require BASE_DIR . APP_DIR . '/views/' . $page . '.php';
			require BASE_DIR . APP_DIR . '/views/footer.php';
		}

		$this->rendered = TRUE;
	}

	public function buildDropdown($data) {
		$select["id"] = isset($data["id"]) ? $data["id"] : "";
		$select["name"] = isset($data["name"]) ? $data["name"] : "" ;
		$select["class"] = isset($data["class"]) ? $data["class"] : "" ;
		$select["disabled"] = isset($data["disabled"]) ? $data["disabled"] : "" ;
		$select["onchange"]= isset($data["onchange"])? $data["onchange"]:"";
		$options["selected"] = isset($data["selected"]) ? $data["selected"] : "" ;
		$options["default"] = isset($data["default"]) ? $data["default"] : "" ;
		$options["options"] = isset($data["options"]) ? $data["options"] : "" ;

		$s = "<select ";
		foreach ($select as $key => $value) {
			if(empty($value))
				continue;
			$s .= $key . " = \"" . $value . "\"";
		}
		$s .= ">";

		if(!empty($options["default"]) && is_array($options["default"])) {
			$s .= "<option value=\"" . $options["default"][0] . "\">" . $options["default"][1] . "</option>";
		}

		foreach ($options["options"] as $values) {
			if(!is_array($values)) {
				$selected = ($options["selected"] == $values) ? "selected=\"selected\"" : "";
				$o = "<option value=\"" . $values . "\" " . $selected . ">" . $values . "</option>";
			} else {
				$selected = ($options["selected"] == $values[0]) ? "selected=\"selected\"" : "";
				$o = "<option value=\"" . $values[0] . "\" " . $selected . ">" . $values[1] . "</option>";
			}
			$s .= $o;
		}
		$s .= "</select>";

		return $s;
	}

}
