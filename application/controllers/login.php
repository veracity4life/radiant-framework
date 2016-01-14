<?php

namespace App\Controllers;

use Radiant\Toolbox\Controller;

/**
*
*/
class Login extends Controller
{
	function __construct() {
		parent::__construct();

	}

	public function getView($view = "index") {
        session_unset();

		$this->view->render('login/' . $view);
	}

	public function checkLogin() {
		if(!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username'])) {
			Session::set('error', "Login credentials were empty or missing.");
			header("Location: " . BASE_URL . "/login");
			exit();
		}

		# Check credentials and then get the account id
		$accountID = $this->model->checkCredentials();
		if(!$accountID) {
			Session::set('error', $this->model->errorMsg);
			header("Location: " . BASE_URL . "/login");
			exit();
		}


		# Check for account info and assign to session
		if(isset($accountID) && !empty($accountID)) {
			Session::set('accountID', $accountID);

			header("Location: " . BASE_URL . "/home");
			exit();
		}

		Session::set('error', "Login has failed.");
		header("Location: " . BASE_URL . "/login");
		exit();
	}



}
