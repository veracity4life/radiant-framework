<?php

/**
*
*/
class Bootstrap
{

	function __construct() 	{
		if(isset($_GET['url']) && !empty($_GET['url'])) {
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode("/", $url);
		} else {
			$url[0] = "login";
		}

		$file = BASE_DIR . "/application/controllers/" . $url[0] . ".php";

		if(file_exists($file)) {
			require $file;
		} else {
			// TODO: Write an error controller to handle these messages.
			die("The file: <b>$file</b> does not exist.");
		}

		$controller = new $url[0];
		/*if(!$controller->acitveSession() && $url[0] != 'login') {
			Session::set("error", "You need to log in before accessing that page or your session has expired.");
			header('Location: ' . BASE_URL . '/login');
			exit();
		}*/

		$controller->loadModel($url[0]);

		if(isset($url[1]) && !empty($url[1])) {
			if(isset($url[2]) && (!empty($url[2]) || in_array($url[2], array(0, '0')))) {
				$args = array_slice($url, 2);
				$controller->{$url[1]}($args);
			} else {
				$controller->{$url[1]}();
			}
		}

		if(!isset($controller->view->rendered)) {
			$controller->getView();
		}
	}
}
