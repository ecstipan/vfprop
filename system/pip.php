<?php

function pip()
{
	global $config;
    
    // Set our defaults
    $controller = $config['default_controller'];
    $action = 'index';
    $url = '';
	
	// Get request url and script url
	$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
	$script_url  = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';
    	
    /*
	if (substr($request_url, -1) != '/') {
		if($request_url != $script_url) $url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');
		header("Location:  http://".$_SERVER['HTTP_HOST'].$request_url."/");
		exit();
	}
	*/

	// Get our url path and trim the / of the left and the right
	if($request_url != $script_url) $url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');

	// Split the url into segments
	$segments = explode('/', $url);
	
	// Do our default checks
	if(isset($segments[0]) && $segments[0] != '') $controller = $segments[0];
	if(isset($segments[1]) && $segments[1] != '') $action = $segments[1];

	// Get our controller file
    $path = APP_DIR . 'controllers/' . $controller . '.php';
	if(file_exists($path)){
        require_once($path);
	} else {
        $controller = $config['error_controller'];
        require_once(APP_DIR . 'controllers/' . $controller . '.php');
	}
    
    // Check the action exists
    if(!method_exists($controller, $action)){
        $controller = $config['error_controller'];
        require_once(APP_DIR . 'controllers/' . $controller . '.php');
        $action = 'index';
    }

	//connect to our database
	$config['db_connection'] = mysqli_connect($config['db_host'], $config['db_username'], $config['db_password'], $config['db_name']) or die("SQL Connection Refused!");
	
	// Create object and call method
	$obj = new $controller;
    call_user_func_array(array($obj, $action), array_slice($segments, 2));
	$config['db_connection']->close();
	die();
}

?>
