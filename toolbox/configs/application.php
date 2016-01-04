<?php

	/**
	 *	ENVIRONMENT (DEV, PROD, localDEV)
	 *
	 *	ERROR_REPORTS (TRUE, FALSE)
	**/

	define('ENVIRONMENT', 'localDEV');

	define('ERROR_REPORTS', TRUE);



	/**
	 *	Paths Config Options
	 *
	**/

	define('BASE_URL', '/radiant-framework');

	define('PUBLIC_DIR_URL', '/radiant-framework/public');

	if(ENVIRONMENT != "localDEV" && ENVIRONMENT != "PROD")
		define('BASE_DIR', '/var/www/radiant-framework');
	else if(ENVIRONMENT != "PROD")
		define('BASE_DIR', '/Applications/MAMP/htdocs/radiant-framework');
	else if(ENVIRONMENT == "PROD")
		define('BASE_DIR', '');

	define('APP_DIR', '/application');
	define('LIB_DIR', '/toolbox/libs');

