<?php

namespace Radiant\Toolbox;

use Radiant\Toolbox\Libs\DBConnection;

/**
*
*/
class Model
{

	function __construct() {

	}

	public function initDB() {
		$this->db_main = new DBConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_SCHEMA, DB_PORT);
	}

	public function newConn($host, $username, $password, $schema, $port) {
		return new DBConnection($host, $username, $password, $schema, $port);
	}

}
