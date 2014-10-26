<?php
global $config;
////////////////// URI MAP STUFF /?///////////////////////
$config['base_url'] 			= 'http://'.getenv("APP_URL").'/'; 		// Base URL including trailing slash (e.g. http://localhost/)
$config['default_controller'] 	= 'main'; 								// Default controller to load
$config['error_controller'] 	= 'error'; 								// Controller used for errors (e.g. 404, 500 etc)

////////// /ADDED CONFIG TO USE WITH HEROKU //////////////
$url=parse_url(getenv("CLEARDB_DATABASE_URL"));
date_default_timezone_set('America/New_York');
$config['db_host'] 				= $url["host"]; 						// Database host
$config['db_name'] 				= substr($url["path"],1); 				// Database name
$config['db_username'] 			= $url["user"]; 						// Database username
$config['db_password'] 			= $url["pass"];							// Database password
$config['db_time_correction'] 	= -60*60*4;								// Database time offset

?>