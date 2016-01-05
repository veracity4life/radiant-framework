<?php

/**
*
*/
class GoogleCloudMessaging
{

	private $apiKey = '';
	private $url = 'https://android.googleapis.com/gcm/send';

	public $registrationIDs;
	public $data;
	public $headers;
	public $fields;

	public $result;
	public $error;

	function __construct($regIDs, $data) {
		if(!isset($data) && !isset($regIDs))
			die("ERROR");
		$this->registrationIDs = $regIDs;
		$this->data = $data;
	}

	public function executeCurl() {
		$this->setFields();
		$this->setHeaders();

		$curl = curl_init();

		curl_setopt( $curl, CURLOPT_URL, $this->url);
		curl_setopt( $curl, CURLOPT_POST, true);
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode($this->fields));

		$this->result = curl_exec($curl);

		curl_close($curl);
	}

	public function addRegID($newID) {
		array_push($this->registrationIDs, $newID);
	}

	public function setHeaders($newData = null) {
		if($newData == null) {
			$this->headers = array(
				'Authorization: key=' .	$this->apiKey,
				'Content-Type: application/json'
			);
		} else {
			$this->headers = $newData;
		}
	}

	public function setFields($newData = null) {
		if($newData == null) {
			$this->fields = array(
				'registration_ids'	=> $this->registrationIDs,
				'collapse_key'		=> 'Pending Alerts Available.',
				'data'				=> $this->data
			);
		} else {
			$this->fields = $newData;
		}
	}
}
