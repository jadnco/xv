<?php require_once("../../includes/init.php"); ?>

<?php

$post_id = $_GET["post"];

unpublish($post_id);

sleep(1);

redirect_to(BASE_URL);

?>