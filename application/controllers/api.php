<?php
/**
* 	API expects an extra header when receiving from iOS : HTTP_METHOD
*	This header can either be set to 'getRequest' or 'postRequest' and should reflect the type of request being used.  By default all API calls should make use of the POST request to help increase security.
*/
class Api extends Controller
{
	private $timestamp;

	function __construct() {
		parent::__construct();

		$date = new DateTime();
		$this->timestamp = $date->format('Y-m-d H:i:s');

		# Check the type of request we are receiving
		$tempRequest = isset($_GET) && count($_GET) > 1 ? $_GET : (isset($_POST) && !empty($_POST) ? $_POST : $this->retrieveRequestBody());
		$requestData = array();

		if(empty($tempRequest) || !$tempRequest) {
			print_r($this->defineResponse("API request data was empty or formatted incorrectly", $tempRequest));
			exit();
		}

		# Check if the request data is set as an array, if not create an array from object data
		if(!is_array($tempRequest) && is_object($tempRequest)) {
			foreach($tempRequest as $property => $value) {
				$requestData[$property] = $value;

				if(!is_array($value) && is_object($value)) {
					$requestData[$property] = array();
					foreach($value as $pr => $v) {
						$requestData[$property][$pr] = $v;
					}
					$requestData[$property] = json_encode($requestData[$property]);
				}
			}
		} else {
			$requestData = $tempRequest;
		}

		# Set up local variables for specific api call and error handling
		$method = isset($requestData["method"]) ? $requestData["method"] : null ;
		$type = isset($requestData["type"]) ? $requestData["type"] : null ;
		$data = isset($requestData["data"]) ? json_decode($requestData["data"], true) : null ;

		if($type == null || empty($type)) {
			print_r($this->defineResponse("No api type found or api type was null.", $type));
			exit();
		}

		if($method == null || empty($method)) {
			print_r($this->defineResponse("No api method found or api method was null.", $method));
			exit();
		}

		# Require the requested api and execute method if found
		require BASE_DIR . APP_DIR . "/apis/" . $type . ".api.php";

		$api = new $type();

		if(method_exists($api, $method)) {
			$result = json_encode($api->$method($data));
			if (isset($api->error)) {
				$result = $api->error;
			}

		} else {
			$result = $this->defineResponse("Requested method was not found or does not exist.", $method);
		}

		print_r($result);
		exit();
	}

	private function retrieveRequestBody() {
		$body = false;

		if($_POST == null || empty($_POST)) {
			if(empty($body) || $body == null) {
				$handler = fopen('php://input', 'r');
				$rawData = fgets($handler);

				$body = json_decode($rawData);
			}
		} else {
			$body = $_POST;
		}

		return $body;
	}

	private function defineResponse($msg = "", $data = "", $status = -4) {
		$response = array();

		$response['status'] = $status;
		$response['message'] = $msg;
		$response['data'] = $data;
		$response['timestamp'] = $this->timestamp;

		return json_encode($response);
	}

}
