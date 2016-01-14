<?php

/**
*
*/
class Security
{
	private $conn;

	private $userRole;
	private $accountID;

	public $error;

	function __construct($dbConn, $accID = null, $userUUID = null) {
		if(!isset($accID) || $accID == null || !isset($dbConn))
			return false;

		$this->conn = $dbConn;
		$this->accountID = $accID;

		if(isset($userUUID) && !empty($userUUID)) {
			$this->getUserRole($userUUID);
		}
	}

	public function getUserRole($userUUID) {
        // SQL statement to retrieve security role based on the account or user info
		$sql = "";

		$sqlResult = $this->conn->get_value($sql);

		$this->userRole = $sqlResult;
	}

	public function checkModuleAccess($mID, $action, $userUUID = null) {
		if(!isset($this->userRole) && !empty($userUUID)) {
			$this->getUserRole($userUUID);
			if(empty($this->userRole)) {
				return $this->error = array(false, "ERROR : User role was not defined or was empty.");
			}
		}

		if(!isset($this->userRole) && empty($userUUID)) {
			return $this->error = array(false, "ERROR : User role was not defined and/or userUUID not provided");
		}

        // SQL to check security access to the individual modules
		$sql = "";

		$sqlResult = $this->conn->get_row($sql);

		return $sqlResult;
	}

	public function getModuleAccess($mID, $userUUID = null) {
		if(!isset($this->userRole) && !empty($userUUID)) {
			$this->getUserRole($userUUID);
			if(empty($this->userRole)) {
				return $this->error = array(false, "ERROR : User role was not defined or was empty.");
			}
		}

		if(!isset($this->userRole) && empty($userUUID)) {
			return $this->error = array(false, "ERROR : User role was not defined and/or userUUID not provided");
		}

        // SQL to retrieve the module access levels for the user
		$sql = "";

		$sqlResult = $this->conn->get_row($sql);

		return $sqlResult;
	}



}
