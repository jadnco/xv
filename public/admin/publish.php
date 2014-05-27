<?php require_once("../../includes/init.php"); ?>

<?php

$post_id    = $_POST["post_id"];
$post_title = $_POST["post_title"];
$post_content = $_POST["post_content"];
$markdown = $_POST["post_markdown"];

if (isset($post_id) && !empty($post_id)) {
	save_post($post_id, $post_title, $post_content, $markdown);
	publish_post($post_id, $post_title);
}

// Delay to give the server time to respond
sleep(1);

echo $post_id . "/" . post($post_id, "title_url");

?>