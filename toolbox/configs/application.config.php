<?php

	/**
	 *	ENVIRONMENT (DEV, DEMO, PROD)
	 *
	 *	ERROR_REPORTS (TRUE, FALSE)
	**/

	define('ENVIRONMENT', 'DEV');

	define('ERROR_REPORTS', TRUE);



	/**
	 *	Paths Config Options
	 *
	**/

	define('BASE_URL', '/radiant-framework');

	define('PUBLIC_DIR_URL', '/radiant-framework/public');

    switch(ENVIRONMENT) {
        case "PROD":
            define('BASE_DIR', '');
            break;

        case "DEMO":
            define('BASE_DIR', '');
            break;

        case "DEV":
        default:
            define('BASE_DIR', '/Applications/MAMP/htdocs/radiant-framework');
            break;
    }


	define('APP_DIR', '/application');
	define('LIB_DIR', '/toolbox/libs');


    if(ERROR_REPORTS) {
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
    }

