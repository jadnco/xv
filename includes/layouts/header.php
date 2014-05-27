<?php
/**
 *	File: includes/layouts/header.php
 *	Desc: Loads the doctype head etc.
 *	Author: Jaden Dessureault
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<base href="http://skills2014.digicandesigns.com/Jaden/xv/public/">
	<title><?php echo isset($title) ? htmlentities($title) : ""; ?> | XV</title>
	<link rel="icon" type="image/png" href="assets/images/favicon.png">
	<meta name="author" content="Jaden Dessureault">
	<meta name="robots" content="noindex, nofollow">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/simple-line-icons.css">
	<link href='http://fonts.googleapis.com/css?family=Montserrat:700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Anonymous+Pro:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
	<script src="assets/js/jquery.min.js"></script>

	<?php if (logged_in()) { ?>
		<link rel="stylesheet" href="assets/css/admin.css">
		<link rel="stylesheet" href="assets/css/crevasse.css">
		<script src="assets/js/crevasse.js"></script>
		<script src="assets/js/jquery.caret.js"></script>
		<script src="assets/js/jquery.scrollTo.min.js"></script>
		<script src="assets/js/marked.js"></script>
		<script src="assets/js/admin.js"></script>
		<script src="assets/js/modernizr.custom.js"></script>
		<script src="http://malsup.github.com/jquery.form.js"></script>
	<?php } ?>

	<?php if ($title == "Setup") echo "<link rel=\"stylesheet\" href=\"assets/css/setup.css\">"; ?>
</head>
<body>
<?php if (logged_in() && $title != "New Post" && $title != "Edit Post") { ?>
	<header id="admin-header">
		<span>Admin</span>
		<span class="header-icon clearfix"><a href="<?php echo BASE_URL . "/new"; ?>" class="no-underline" title="Create new post"><i class="icon-plus"></i></a></span>
		<span class="user-name"><?php echo user("first_name") . " " . user("last_name"); ?></span>
		<a class="logout no-underline" href="<?php echo BASE_URL . "/logout"; ?>">Logout</a>
	</header>
<?php } ?>
