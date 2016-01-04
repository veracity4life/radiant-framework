<?php

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
		$path = BASE_DIR . APP_DIR . "/models/" . $label . "_model.php";

		if(file_exists($path)) {
			require $path;

			$modelName = $label . "_Model";
			$this->model = new $modelName();

/*			if(Session::get("id") != FALSE)*/
				$this->model->initDB();
		}
	}


}
