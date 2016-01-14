<?php

namespace Radiant\Toolbox;

use Radiant\Toolbox\Helpers\Session;

/**
*
*/
class Controller
{
	function __construct() {
		Session::init();
		$this->view = new View();

	}

	public function acitveSession() {
		$sessionID = Session::get('id');

		if($sessionID == null || empty($sessionID)) {
			return FALSE;
		}

		return TRUE;
	}

	public function loadModel($label) {
		$fqn = "App\\Models\\" . $label;

		if(class_exists($fqn)) {
			$this->model = new $fqn();
            $this->model->initDB();
		}
	}


}
