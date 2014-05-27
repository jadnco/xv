<?php require_once("../../includes/init.php"); ?>

<?php

$post_title   = $_POST["post_title"];
$post_content = $_POST["post_content"];
$markdown     = $_POST["post_markdown"];
$header_image = $_POST["header_image"];

if ((isset($markdown) && !empty($markdown)) || (isset($post_title) && !empty($post_title))) {
	create_post($post_title, $post_content, $markdown, $header_image);
}

// Delay to give the server time to respond
sleep(1);

echo last_post();

?>