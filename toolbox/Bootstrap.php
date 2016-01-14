<?php

namespace Radiant\Toolbox;

use Radiant\Toolbox\Config;
use App\Controllers\Error;

/**
*
*/
class Bootstrap
{
	
	function __construct() 	{

        // Load all defined config files
        Config::loadConfigs();


        // Check get values from .htaccess routing to determine app route
		if (isset($_GET['url']) && !empty($_GET['url'])) {
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode("/", $url);
		} else {
			$url[0] = "home";
		}

		$file = BASE_DIR . "/application/controllers/" . $url[0] . ".php";

		if (file_exists($file)) {
			require $file;
            $controller = new $url[0];
		} else {
			// TODO: Write an error controller
//			require \Radiant\BASE_DIR . "/application/controllers/error.php";
            $controller = new Error();
		}

		/*if (!$controller->acitveSession() && $url[0] != 'login') {
			Session::set("error", "You need to log in before accessing that page or your session has expired.");
			header('Location: ' . BASE_URL . '/login');
			exit();
		}*/

		$controller->loadModel($url[0]);

		if (isset($url[1]) && !empty($url[1])) {
			if (isset($url[2]) && (!empty($url[2]) || in_array($url[2], array(0, '0')))) {
				$args = array_slice($url, 2);
				$controller->{$url[1]}($args);
			} else if (method_exists($controller,$url[1])) {
				$controller->{$url[1]}();
			}
		}

		if (!isset($controller->view->rendered)) {
            if (isset($url[1]) && !method_exists($controller,$url[1])) {
                $controller->getView($url[1]);
            } else {
                $controller->getView();
            }
		}
	}
}
