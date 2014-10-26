<?php
/*
 * PIP v0.5.3
 */

if (getenv("PROD_STATUS") == "false") {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
} else {
	ini_set('display_errors', '0');
}

// Defines
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('APP_DIR', ROOT_DIR .'application/');

//New Session Handler
$m = new Memcached("memcached_pool");
$m->setOption(Memcached::OPT_BINARY_PROTOCOL, TRUE);

// some nicer default options
$m->setOption(Memcached::OPT_NO_BLOCK, TRUE);
$m->setOption(Memcached::OPT_AUTO_EJECT_HOSTS, TRUE);
$m->setOption(Memcached::OPT_CONNECT_TIMEOUT, 2000);
$m->setOption(Memcached::OPT_POLL_TIMEOUT, 2000);
$m->setOption(Memcached::OPT_RETRY_TIMEOUT, 2);

// setup authentication
$m->setSaslAuthData( getenv("MEMCACHIER_USERNAME")
	, getenv("MEMCACHIER_PASSWORD") );

// We use a consistent connection to memcached, so only add in the
// servers first time through otherwise we end up duplicating our
// connections to the server.
if (!$m->getServerList()) {
	// parse server config
	$servers = explode(",", getenv("MEMCACHIER_SERVERS"));
	foreach ($servers as $s) {
		$parts = explode(":", $s);
		$m->addServer($parts[0], $parts[1]);
	}
}

// Includes
require(APP_DIR . 'config/config.php');
require(ROOT_DIR .'system/model.php');
require(ROOT_DIR .'system/view.php');
require(ROOT_DIR .'system/controller.php');
require(ROOT_DIR .'system/pip.php');

// Define base URL
global $config;
define('BASE_URL', $config['base_url']);

pip();

?>
