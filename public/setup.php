<?php
/**
 *	File: public/setup.php
 *	Desc: Display on installation, create the tables and first admin
 *	Author: Jaden Dessureault
 */
?>

<?php $title = "Setup"; ?>

<?php require_once("../includes/init.php"); ?>

<?php

if (isset($db_name) || !empty($db_name)) redirect_to(BASE_URL);

$first_name = "";
$last_name  = "";
$user_name  = "";
$blog_name  = isset($_POST["blog_name"]) ? space_to_underscore(strtolower($_POST["blog_name"])) : "";

$step = isset($_GET["step"]) ? $_GET["step"] : "";
$steps = array(1 => "Name your blog", 2 => "Configure", 3 => "Create admin");

$errors = array();

$blog_name_err = isset($_GET["b"]) ? $_GET["b"] : "";

if ($blog_name_err == 1) {
	$errors[] = "Please enter a blog name.";
}

if ($step == 3) {
	if (empty($db_name)) redirect_to("../2");
}

if ($step == 2) {
	if (empty($blog_name)) redirect_to("../1");
}

// When the user submits the blog name form
if (isset($_POST["submit_db"])) {
	if (empty($blog_name)) {
		$errors[] = "Please enter a blog name.";
	}

	if (has_special_chars($blog_name)) {
		$errors[] = "The blog name can't have fancy characters.";
	}

	if (!count($errors)) create_database($blog_name);
}

// When the user submits the user form
if (isset($_POST["user_submit"])) {
	$first_name = ucfirst(trim($_POST["first_name"]));
	$last_name  = ucfirst(trim($_POST["last_name"]));
	$user_name  = trim($_POST["user_name"]);
	$password   = $_POST["password"];

	// Make sure all fields are filled in
	if (empty($first_name) || empty($last_name) || empty($user_name) || empty($password)) {
		$errors[] = "Please fill in all fields.";
	} else {
		// Make sure the user doesn't enter any special characters
		if (has_special_chars($user_name)) {
			$errors[] = "The username can't have fancy characters.";
		}

		// Check to see if there is a space in the username
		if (strpos($user_name, " ") > 0) {
			$errors[] = "You can't have a space in your username.";
		}

		// Make sure the user doesn't enter a short password
		if (strlen($password) <= 5 && strlen($password) > 0) {
			$errors[] = "Your password should be longer then 5 characters.";
		}
	}

	// There are no errors
	if (!count($errors)) {
		// Create the posts table
		create_table($db_post_table, posts_table(), "id");

		// Create the users table
		create_table($db_user_table, users_table(), "id");

		// Create the first admin
		create_user($user_name, $first_name, $last_name, $password, true);

		redirect_to(BASE_URL);
	}
}

?>

<?php include_once(layout("header.php")); ?>

<div class="setup-wrap">
	<h4><?php echo !empty($step) ? $steps[$step] : $steps[1]; ?></h4>

	<?php if ($step == 1 || $step == "") { ?>
		<p>This will be the name of a new database.</p>
		<form class="blog-name-form clearfix" method="post" action="../setup/2">
			<input type="text" name="blog_name" placeholder="Blog Name">
			<input type="submit" name="submit_db" value="Confirm">
		</form>
		<?php if (isset($errors) && !empty($errors)) { ?>
			<div class="errors"><?php echo join(" " , $errors); ?></div>
		<?php } ?>
	<?php } ?>

	<?php if ($step == 2) { ?>
		<p>The database <strong><?php echo $blog_name; ?></strong> has been created.</p>
		<p class="go-config">Go to <strong>includes/config.php</strong> and change <strong>$db_name</strong></p>
		<code>$db_name = "<?php echo $blog_name; ?>";</code>
		<a class="next-button" href="../setup/3">Next</a>
	<?php } ?>

	<?php if ($step == 3) { ?>
		<p>Create the first admin user.</p>
		<form class="create-user-form" method="post" action="../setup/3">
			<input type="text" name="first_name" placeholder="First Name" value="<?php echo $first_name; ?>">
			<input type="text" name="last_name" placeholder="Last Name" value="<?php echo $last_name; ?>">
			<input type="text" name="user_name" placeholder="Username" value="<?php echo $user_name; ?>">
			<input type="password" name="password" placeholder="Password">
			<input type="submit" name="user_submit">
		</form>
		<?php if (!empty($errors) && count($errors)) { ?>
			<p class="errors"><?php echo join(" ", $errors); ?></p>
		<?php } ?>
	<?php } ?>

	<ul class="step-dots">
		<?php if (empty($step)) { ?>
			<li class="current"></li>
			<?php for ($i = 0; $i < count($steps) - 1; $i++) { ?>
				<li></li>
			<?php } ?>
		<?php } else { ?>
		<?php foreach ($steps as $step_num => $step_name) { ?>
			<li class="<?php echo ($step == $step_num) ? "current" : ""; ?>"></li>
		<?php } } ?>
	</ul>
</div>


<?php include_once(layout("footer.php")); ?>