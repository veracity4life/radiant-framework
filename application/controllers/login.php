<?php

/**
*
*/
class Login extends Controller
{

	function __construct() {
		parent::__construct();

	}

	public function getView($view = "index") {
		$this->view->PAGE_TITLE = "Login";
		$this->view->render('login/' . $view);
	}

	public function checkLogin() {
		if(!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username'])) {
			Session::set('error', "Login credentials were empty or missing.");
			header("Location: " . BASE_URL . "/login");
			exit();
		}

		# Check credentials and then get the property id
//		$propID = $this->model->checkCredentials();
		if(!$propID) {
			Session::set('error', $this->model->errorMsg);
			header("Location: " . BASE_URL . "/login");
			exit();
		}


		# Check for property info and assign to session
		if(isset($propID) && !empty($propID)) {
			Session::set('propertyID', $propID);

			header("Location: " . BASE_URL . "/home");
			exit();
		}

		Session::set('error', "Login has failed.");
		header("Location: " . BASE_URL . "/login");
		exit();
	}



}
