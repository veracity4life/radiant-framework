<?php

namespace Radiant\Toolbox\Libs;

/**
*
*/
class iOSNotifications
{

	private $apnsHostUrl = "gateway.sandbox.push.apple.com:2195";
	private $apnsConnection = false;

    // Passphrase and LocalCert are projcet specific
	private $passphrase = "";
	private $localCert = "";


	private $deviceTokens = array();
	private $message;

	public $errorMsg;
	public $result;

	function __construct($msg, $tokens = null) {
		$this->message = $msg;

		if($tokens != null && count($tokens) > 0) {
			$this->deviceTokens = $tokens;
		}
	}

	public function connectToAPNS() {
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', BASE_DIR . "/" . LIB_DIR . "/" . $this->localCert);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passphrase);

		# Open connection to APNS
		$this->apnsConnection = stream_socket_client("ssl://" . $this->apnsHostUrl, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $ctx);

		if (!isset($this->apnsConnection) || !$this->apnsConnection) {
			$this->errorMsg = "Failed to connect: $error $errorString" . PHP_EOL;
			$this->error();
		}

		stream_set_blocking ($this->apnsConnection, 0);
	}

	public function closeConnection() {
		fclose($this->apnsConnection);
	}


	public function sendNotifications() {
		if(count($this->deviceTokens) < 1) {
			$this->setDeviceTokens(array());
		}

		if(!isset($this->apnsConnection) || empty($this->apnsConnection)) {
			$this->connectToAPNS();
		}

		# Create and Encode payload
		$body = array();
		$body['aps'] = array('alert' => array(), 'badge' => 0, 'sound' => "default");
		$body['aps']['alert'] = array('action-loc-key' => "Open", 'body' => $this->message);
		$payload = json_encode($body);

		#Loop through deviceToken list and create and send binary for each device found
		foreach ($this->deviceTokens as $key => $device) {
			$device = str_replace(" ", "", $device);

			# Message format is, |COMMAND|TOKENLEN|TOKEN|PAYLOADLEN|PAYLOAD|
			$binMsg = chr(0) . pack('n', 32) . pack('H*', $device) . pack('n', strlen($payload)) . $payload;
			$this->result[$key] = fwrite($this->apnsConnection, $binMsg);

			if ($this->result[$key]) {
				$this->result[$key] = "Successful, message sent." . PHP_EOL;
			} else {
				$this->result[$key] = $this->checkAppleResponse();
			}
		}

		$this->closeConnection();
	}

	public function error() {
		if(isset($this->apnsConnection) || $this->apnsConnection == true) {
			$this->closeConnection();
		}

		exit($this->errorMsg);
	}

	public function checkAppleResponse($curResult = false) {
		$returnResponse = array('command' => null, 'status_code' => null, 'msg' => null);
		$response = ($curResult == false ? fread($this->apnsConnection, 6) : $curResult );

		if(!$response || empty($response)) {
			$returnResponse['msg'] = "No response found.";
			return $curResult || $returnResponse;
		}

		$unpackedResponse = unpack('Ccommand/Cstatus_code/Nidentifier', $response);

		switch ($unpackedResponse['status_code']) {
			case '0':
				$returnResponse['msg'] = "No errors encountered";
				break;
			case '1':
				$returnResponse['msg'] = "Processing error";
				break;
			case '2':
				$returnResponse['msg'] = "Missing device token";
				break;
			case '3':
				$returnResponse['msg'] = "Missing topic";
				break;
			case '4':
				$returnResponse['msg'] = "Missing payload";
				break;
			case '5':
				$returnResponse['msg'] = "Invalid token size";
				break;
			case '6':
				$returnResponse['msg'] = "Invalid topic size";
				break;
			case '7':
				$returnResponse['msg'] = "Invalid payload size";
				break;
			case '8':
				$returnResponse['msg'] = "Invalid token";
				break;

			default:
				$returnResponse['msg'] = "None (unknown) or Not Listed";
				break;
		}

		$returnResponse['command'] = $unpackedResponse['command'];
		$returnResponse['status_code'] = $unpackedResponse['status_code'];

		return $returnResponse;
	}

	public function setMesasge($msg) {
		$this->message = $msg;
	}

	public function getMesasge() {
		return $this->message;
	}

	public function setDeviceTokens($tokenList) {
		if(count($tokenList) < 1 || empty($tokenList[0])) {
			$this->errorMsg = "Unable to send notification. No tokens found in list.";
			$this->error();
		}

		$this->deviceTokens = $tokenList;
	}
}
