<?php

/**
*
*/
class Error extends Controller
{
    function __construct() {
		parent::__construct();

	}

	public function getView($view = "index") {

		$this->view->render('error/' . $view);
	}

    public function fourzerofour() {
		$this->getView("404");
	}





}
