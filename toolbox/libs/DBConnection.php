<?php

/**
*   Based off of a previously used DB connection class and updated for the mysqli functions.  Not sure of original source code.
*/
class DBConnection
{

	public $conn;

	function __construct($host, $username, $password, $database, $port) {
		$this->conn = new Mysqli($host, $username, $password, $database, intval($port));

		if (mysqli_connect_errno()) {
			trigger_error('Database connection failed: '  . mysqli_connect_error(), E_USER_ERROR);
		}
	}

	function __destruct() {
		$this->conn->close();
		// mysqli_close($this->conn);
	}

	public function error() {
		return mysqli_error($this->conn);
	}

	private function perform($parameters) {
		$sql = '';
		if ((count($parameters) == 2) && is_array($parameters[1])) {
			for ($i = count($parameters[1]); $i >= 0; $i-=1) {
				$parameters[$i+1] = $parameters[1][$i];
			}
		}
		$bindings = explode('?', $parameters[0]);
		for ($i = 0; $i < count($bindings) - 1; $i+=1) {
			$value = mysqli_real_escape_string(trim($parameters[$i+1]));
			$sql .= $bindings[$i] . (($value != '')?("'" . $value . "'"):"NULL");
		}
		$sql .= $bindings[count($bindings) - 1];

		if($result = $this->conn->query($sql)) {

		} else {
			print_r($this->error());
		}

		return $result;
	}

	public function execute() {
		$result = $this->perform(func_get_args());
		return;
	}

	public function insert() {
		$result = $this->perform(func_get_args());
		return mysqli_insert_id($this->conn);
	}

	public function insert_from_hash($table, $hash, $append = '') {
		if (count($hash) == 0)
			return;
		$columns = array();
		$values = array();
		$bindings = array();
		foreach ($hash as $key => $value) {
			array_push($columns, $key);
			array_push($values, $value);
			array_push($bindings, '?');
		}
		array_unshift($values, "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $bindings) . ") $append");

		$result = $this->perform($values);
		return mysqli_insert_id($this->conn);
	}

	public function update_from_hash($table, $hash, $index, $id) {
		if (count($hash) == 0)
			return;
		$columns = array();
		$values = array();
		foreach ($hash as $key => $value) {
			array_push($columns, $key);
			array_push($values, $value);
		}
		array_unshift($values, "UPDATE $table SET " . implode(' = ?, ', $columns) . " = ? WHERE $index = '$id'");
		$result = $this->perform($values);
		return;
	}

	public function get_array() {
		$result = $this->perform(func_get_args());
		$array = array();
		while ($row = $result->fetch_array(MYSQLI_NUM)) {
			array_push($array, $row);
		}
		mysqli_free_result($result);
		return $array;
	}

	public function get_list() {
		$result = $this->perform(func_get_args());
		$array = array();
		while ($row = $result->fetch_array(MYSQLI_NUM)) {
			array_push($array, $row[0]);
		}
		mysqli_free_result($result);
		return $array;
	}

	public function get_hash() {
		$result = $this->perform(func_get_args());
		$array = array();
		while ($row = $result->fetch_array(MYSQLI_NUM)) {
			$array[$row[0]] = $row[1];
		}
		mysqli_free_result($result);
		return $array;
	}

	public function get_multi_hashref() {
		$result = $this->perform(func_get_args());
		$array = array();
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$array[] = $row;
		}

		return $array;
	}

	public function get_hashref() {
		$result = $this->perform(func_get_args());
		return $result->fetch_array(MYSQLI_ASSOC);
	}

	public function get_row() {
		$result = $this->perform(func_get_args());
		$array = array();
		if ($row = $result->fetch_array(MYSQLI_NUM)) {
			mysqli_free_result($result);
			return $row;
		}
		mysqli_free_result($result);
		return null;
	}

	public function get_value() {
		$result = $this->perform(func_get_args());
		$array = array();
		if ($row = $result->fetch_array(MYSQLI_NUM)) {
			mysqli_free_result($result);
			return $row[0];
		}
		mysqli_free_result($result);
		return null;
	}

	public function close_conn() {
		$this->conn->close();
		// mysqli_close($this->conn);
	}

}

