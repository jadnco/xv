<?php
/**
 *	File: includes/config.php
 *	Desc: Configurations that the user will supply
 *	Author: Jaden Dessureault
 */

// MySQL credentials
$db_user = "";
$db_pass = "";
$db_name = "";

$db_prefix     = "xv_";
$db_user_table = $db_prefix . "users";
$db_post_table = $db_prefix . "posts";

// Allowed file extensions for header images
$header_exts = array("gif", "jpeg", "jpg", "png");

// Max file size
$max_header_size = '900kb';

// Timezone
date_default_timezone_set("America/Winnipeg");

?>
