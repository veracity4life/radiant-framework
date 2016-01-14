<?php

/*
 *
 */
namespace Radiant;

	/**
	 *	ENVIRONMENT (DEV, DEMO, PROD)
	 *
	 *	ERROR_REPORTS (TRUE, FALSE)
	**/

	define('Radiant\ENVIRONMENT', 'DEV');

	define('Radiant\ERROR_REPORTS', TRUE);



	/**
	 *	Paths Config Options
	 *
	**/

	define('Radiant\BASE_URL', '/radiant-framework');

	define('Radiant\PUBLIC_DIR_URL', '/radiant-framework/public');

    switch(ENVIRONMENT) {
        case "PROD":
            define('Radiant\BASE_DIR', '');
            break;

        case "DEMO":
            define('Radiant\BASE_DIR', '');
            break;

        case "DEV":
        default:
            define('Radiant\BASE_DIR', '/Applications/MAMP/htdocs/radiant-framework');
            break;
    }


	define('Radiant\APP_DIR', '/application');
	define('Radiant\LIB_DIR', '/toolbox/libs');


    if(ERROR_REPORTS) {
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
    }

