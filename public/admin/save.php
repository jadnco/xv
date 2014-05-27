<?php require_once("../../includes/init.php"); ?>

<?php

// Delay to give the server time to respond
sleep(1);

$post_title   = $_POST["post_title"];
$post_content = $_POST["post_content"];
$markdown     = $_POST["post_markdown"];
$header_image = $_POST["header_image"];
$post_id      = $_POST["post_id"];

if (empty($post_title)) {
	echo "Need title";
	exit;
}
save_post($post_id, $post_title, $post_content, $markdown, $header_image);

?>