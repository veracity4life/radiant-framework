<?php

/**
*
*/
class Ajax extends Controller
{

	function __construct() {
		parent::__construct();

		$method = isset($_POST["method"]) ? $_POST["method"] : null ;
		$type = isset($_POST["type"]) ? $_POST["type"] : null ;
		$info = isset($_POST["data"]) ? (!is_string($_POST["data"]) ? $_POST["data"] : json_decode($_POST["data"], true)) : null ;

        $date = new DateTime();
		$timestamp = $date->format('Y-m-d H:i:s');

		if($type == null || empty($type)) {
			$result = json_encode(array('-4', "No ajax type found or ajax type was null.", "", $timestamp));
			print_r($result);
			exit;
		}

		if($method == null || empty($method)) {
			$result = json_encode(array('-4', "No ajax method found or ajax method was null.", "", $timestamp));
			print_r($result);
			exit;
		}

		require BASE_DIR . APP_DIR . "/ajax/" . $type . ".ajax.php";

		$api = new $type();
		$result = json_encode($api->$method($info));
		if (isset($api->error)) {
			$result = $api->error;
		}

		print_r($result);
		exit;
	}




}
