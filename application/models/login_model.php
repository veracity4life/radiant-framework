<?php

/**
*
*/
class Login_Model extends Model
{
	public $errorMsg;

	function __construct() {
		parent::__construct();
	}

	public function getPropertyList() {
		$sql = "SELECT id, name FROM Property WHERE organizationID = 1";
		$propList = $this->db_main->get_array($sql);

		return $propList;
	}

	public function checkCredentials() {

		# Check login credentials against the DB
		$sql = "SELECT uuid, propertyID FROM User WHERE emailPrimary = '" . mysqli_real_escape_string($this->db_main->conn, $_POST['username']) . "' AND webAccess = 1 AND webPassword = '" . md5($_POST['password']) . "'";
		$sqlResult = $this->db_main->get_row($sql);

		if(empty($sqlResult) || !isset($sqlResult)) {
			$this->errorMsg = "Login credentials were invalid or no matches found.";
			return false;
		}

		if(!empty($sqlResult[1]) && !empty($sqlResult[0])) {
			require BASE_DIR . "/" . LIB_DIR . "/Security.php";

			$sec = new Security($this->db_main, $sqlResult[1], $sqlResult[0]);
			$moduleAccess = $sec->checkModuleAccess(1, "view");

			if(!$moduleAccess || (is_array($moduleAccess) && empty($moduleAccess[0]))) {
				$this->errorMsg = $moduleAccess[1];
				return false;
			}
		} else {
			$this->errorMsg = "Login credentials were invalid or account does not have sufficient access.";
			return false;
		}

		return $sqlResult[1];
	}

}
