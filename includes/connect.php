<?php
/**
 *	File: includes/connect.php
 *	Desc: Opens a connection to the MySQL database
 *	Author: Jaden Dessureault
 */

// MySQL connection
$mysqli = new mysqli("localhost", $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
	echo "No db connection ";
	exit;
}

?>
