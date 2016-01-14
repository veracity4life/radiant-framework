<?php

/*
 *
 */
namespace Radiant;

	/**
	 *	Store DB information for dev and production instances
	 *
	 *	Determine mapping based on the value of ENVIRONMENT (DEV, PROD)
	**/

	// Development Config Settings
	$config_dev['host'] = "";
	$config_dev['schema'] = "";
	$config_dev['username'] = "";
	$config_dev['password'] = "";
	$config_dev['port'] = "";

	// Production Config Settings
	$config_prod['host'] = "";
	$config_prod['schema'] = "";
	$config_prod['username'] = "";
	$config_prod['password'] = "";
	$config_dev['port'] = "";


	$config_db = array();

	if(ENVIRONMENT == "DEV" || (ENVIRONMENT != "DEV" && ENVIRONMENT != "PROD")) {
		$config_db = $config_dev;
	}

	if(ENVIRONMENT == "PROD") {
		$config_db = $config_prod;
	}


	/**
	 *	Constant definitions for DB connections
	 *
	**/

	define('Radiant\DB_HOST', $config_db['host']);
	define('Radiant\DB_SCHEMA', $config_db['schema']);
	define('Radiant\DB_USERNAME', $config_db['username']);
	define('Radiant\DB_PASSWORD', $config_db['password']);
	define('Radiant\DB_PORT', $config_db['port']);
