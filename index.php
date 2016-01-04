<?php

	// Configs
	require "toolbox/configs/application.php";
	require "toolbox/configs/database.php";


	if(ERROR_REPORTS) {
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}

	ini_set('memory_limit', '128M');


	// Libs
	require "toolbox/libs/DBConnection.php";
	require "toolbox/libs/Session.php";

	// Toolbox
	require "toolbox/Bootstrap.php";
	require "toolbox/Controller.php";
	require "toolbox/Model.php";
	require "toolbox/View.php";


	// Initiate Application
	$app = new Bootstrap();


