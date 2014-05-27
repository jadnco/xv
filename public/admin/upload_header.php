<?php require_once("../../includes/init.php"); ?>

<?php

$file_info = $_FILES["header_image"];
$post_id = $_GET["post"];

echo upload_image($post_id, $file_info);

?>