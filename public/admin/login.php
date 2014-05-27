<?php $title = "Login"; ?>
<?php require_once("../../includes/init.php"); ?>

<?php

if (logged_in()) redirect_to(BASE_URL);

$errors = "";

if (isset($_POST["login_submit"])) {
	$username = trim($_POST["user_name"]);
	$password = trim($_POST["password"]);

	$login = login($username, $password);

	if ($login === true) redirect_to(BASE_URL);

	$errors = $login;
}

?>

<?php include_once(layout("header.php")); ?>

<div class="login">
	<h4>Admin Login</h4>
	<p>Login to create, edit, and delete posts.</p>
	<form class="login-form clearfix" method="post" action="">
		<input type="text" name="user_name" placeholder="Username" value="">
		<input type="password" name="password" placeholder="Password">
		<input type="submit" name="login_submit">
	</form>
	<?php if (!empty($errors) && count($errors)) { ?>
		<p class="errors"><?php echo join(" ", $errors); ?></p>
	<?php } ?>
</div>

<?php include_once(layout("footer.php")); ?>