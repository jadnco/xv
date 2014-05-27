<?php
/**
 *	File: includes/init.php
 *	Desc: Loads all necassary files; functions, connect etc.
 *	Author: Jaden Dessureault
 */

// Turn off error reporting
error_reporting(0);

// Start the session
session_start();

// Create new path constants
if (!defined("ROOT_PATH")) {
	define("ROOT_PATH", "");
}

if (!defined("INC_PATH")) {
	define("INC_PATH", ROOT_PATH . "/includes/");
}

if (!defined("BASE_URL")) {
	define("BASE_URL", "");
}

// Config file
require_once(INC_PATH . "config.php");

// MySQL connection
require_once(INC_PATH . "connect.php");

// Main functions
require_once(INC_PATH . "functions.php");

?>
