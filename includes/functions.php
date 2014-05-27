<?php
/**
 *	File: includes/functions.php
 *	Desc: General functions
 *	Author: Jaden Dessureault
 */

function redirect_to($new_location = null) {
	if (isset($new_location)) {
		header("Location: {$new_location}");
		exit;
	}
}

function layout($file) {
	return INC_PATH . "layouts/" . $file;
}

function sha256($string) {
	return hash("sha256", $string);
}

// Convert spaces in a string to underscores
function space_to_underscore($string) {
	$string = trim($string);

	// Make sure there are some spaces
	if (strpos($string, " ") > 0) {
		$string = str_replace(" ", "_", $string);
	}

	return $string;
}

function size($size) {
	$number = substr($size, 0, -2);

	switch(strtoupper(substr($size, -2))) {
		case 'KB':
			return $number * 1024;
		case 'MB':
			return $number * pow(1024, 2);
		case 'GB':
			return $number * pow(1024, 3);
		default:
			return $size;
	}
}

// Check a string for any special characters
function has_special_chars($string) {
	$special_chars = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "+", "=", "[", "]", "{", "}", "|", "\\", ";", ":", "'", "\"", "<", ">", "?", "/", ",", ".");

	// Loop through the array, check to see if there are any special characters
	foreach ($special_chars as $char) {
		if (strpos($string, $char) > -1) {
			return true;
		}
	}

	return false;
}

// Check the database to see if a table already exists
function table_exists($table_name) {
	global $mysqli;
	global $db_name;

	$result = $mysqli->query("SHOW TABLES");
	$tables = array();

	// Store all table names in an array
	while ($row = $result->fetch_assoc()) {
		$tables[] = $row["Tables_in_" . $db_name];
	}

	if (in_array($table_name, $tables)) {
		return true;
	}

	return false;
}

function user_exists($username) {
	global $mysqli;
	global $db_user_table;

	$result = $mysqli->query("SELECT username FROM {$db_user_table} WHERE username = '{$username}'");

	if ($result->num_rows > 0) {
		return true;
	}

	return false;
}

// Create a new MySQL database
function create_database($db_name, $prefix = "") {
	global $mysqli;
	global $db_prefix;

	if (isset($db_name) && !empty($db_name))  {
		$prefix = $db_prefix;
		$db_name = strtolower($prefix . space_to_underscore($db_name));

		// Build the query
		$query = "CREATE DATABASE IF NOT EXISTS {$db_name}";

		// Execute the query
		$mysqli->query($query);

		return $db_name;
	}
}

// Create a new MySQL table
function create_table($table_name, $fields = array(), $primary_key) {
	global $mysqli;
	global $db_prefix;

	// Make sure all arguments are supplied
	if (isset($table_name) && isset($primary_key) && count($fields) && is_array($fields)) {
		$prefix = $db_prefix;
		$table_name = $prefix . space_to_underscore($table_name);

		// Make sure the table doesn't already exist
		if (!table_exists($table_name)) {
			// Build the query
			$query  = "CREATE TABLE IF NOT EXISTS {$table_name} (";
			foreach ($fields as $field) {
				$default = "";

				if (isset($field["default"])) {
					$default = " DEFAULT '" . $field["default"] . "'";
				}

				$query .= $field["name"] . " " . $field["type"] . " " . $field["init"] . $default;

				if ($field["increment"] == "yes") {
					$query .= " AUTO_INCREMENT";
				}

				$query .= ', ';
			}

			$query .= "PRIMARY KEY({$primary_key})";
			$query .= ")";

			// Execute the query
			$mysqli->query($query);
		}

		return $table_name;
	}

	return false;
}

function create_user($username, $first_name, $last_name, $password, $admin = false) {
	global $mysqli;
	global $db_user_table;

	if (!user_exists($username)) {
		$join_date  = date("Y-m-d H:i:s");

		// Escape all strings
		$username   = $mysqli->real_escape_string($username);
		$first_name = $mysqli->real_escape_string(ucfirst($first_name));
		$last_name  = $mysqli->real_escape_string(ucfirst($last_name));
		$password   = sha256($password);

		if ($admin === true) {
			$admin = 1;
		} else {
			$admin = 0;
		}

		// Build the query
		$query  = "INSERT INTO {$db_user_table} (";
		$query .= "username, password, first_name, last_name, join_date, admin";
		$query .= ") VALUES (";
		$query .= "'{$username}', '{$password}', '{$first_name}', '{$last_name}', '{$join_date}', '{$admin}')";

		// Execute the query
		$mysqli->query($query);

		return $username;
	}

	return false;
}

function posts_table() {
	$table = array(
		array("name" => "id",               "type" => "int",          "init" => "NOT NULL", "increment" => "yes"),
		array("name" => "post_title",       "type" => "varchar(255)", "init" => "NULL",     "increment" => "no"),
		array("name" => "post_content",     "type" => "text",         "init" => "NULL",     "increment" => "no"),
		array("name" => "markdown_content", "type" => "text",         "init" => "NULL",     "increment" => "no"),
		array("name" => "header_image",     "type" => "varchar(255)", "init" => "NULL",     "increment" => "no"),
		array("name" => "title_url",        "type" => "varchar(255)", "init" => "NULL",     "increment" => "no"),
		array("name" => "user_id",          "type" => "int",          "init" => "NULL",     "increment" => "no"),
		array("name" => "draft_date",       "type" => "datetime",     "init" => "NULL",     "increment" => "no", "default" => "0000-00-00 00:00:00"),
		array("name" => "publish_date",     "type" => "datetime",     "init" => "NULL",     "increment" => "no", "default" => "0000-00-00 00:00:00"),
		array("name" => "published",        "type" => "tinyint(1)",   "init" => "NOT NULL", "increment" => "no", "default" => "0")
	);

	return $table;
}

function users_table() {
	$table = array(
		array("name" => "id",         "type" => "int",          "init" => "NOT NULL", "increment" => "yes"),
		array("name" => "username",   "type" => "varchar(255)", "init" => "NULL",     "increment" => "no"),
		array("name" => "password",   "type" => "varchar(64)",  "init" => "NULL",     "increment" => "no"),
		array("name" => "first_name", "type" => "varchar(255)", "init" => "NULL",     "increment" => "no"),
		array("name" => "last_name",  "type" => "varchar(255)", "init" => "NULL",     "increment" => "no"),
		array("name" => "join_date",  "type" => "datetime",     "init" => "NULL",     "increment" => "no", "default" => "0000-00-00 00:00:00"),
		array("name" => "admin",      "type" => "tinyint(1)",   "init" => "NOT NULL", "increment" => "no", "default" => "0")
	);

	return $table;
}

function title_to_url($post_title) {
	$special_chars = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "+", "=", "[", "]", "{", "}", "|", "\\", ";", ":", "'", "\"", "<", ">", "?", "/", ",", ".");

	$post_title = trim($post_title);
	$post_title = strtolower($post_title);
	$post_title = str_replace(" ", "-", $post_title);
	$post_title = str_replace("_", "-", $post_title);

	foreach ($special_chars as $char) {
		$post_title = str_replace($char, "", $post_title);
	}

	return $post_title;
}

function login($username, $password) {
	global $mysqli;
	global $db_user_table;

	$user_id = "";
	$user_password = "";

	$errors = array();

	if (empty($username) || empty($password)) {
		$errors[] = "Fill in both fields.";
		return $errors;
	}

	if (!user_exists($username)) {
		$errors[] = "That user doesn't exist.";
		return $errors;
	}

	$get_id = $mysqli->query("SELECT id FROM {$db_user_table} WHERE username = '{$username}'");

	while ($id = $get_id->fetch_assoc()) {
		$user_id = $id["id"];
	}

	$get_password = $mysqli->query("SELECT password FROM {$db_user_table} WHERE id = '{$user_id}'");

	while ($pass = $get_password->fetch_assoc()) {
		$user_password = $pass["password"];
	}

	if (sha256($password) == $user_password) {
		$_SESSION["user_id"] = $user_id;
		return true;
	} else {
		$errors[] = "Couldn't login.";
	}

	return $errors;

}

function logout() {
	session_unset();
	session_destroy();
}

function logged_in() {
	if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) return true;
	return false;
}

function create_post($post_title = "", $post_content = "", $markdown = "", $header_image = "") {
	global $mysqli;
	global $db_post_table;

	$post_content = htmlentities($post_content);

	// Escape all strings
	$post_title   = $mysqli->real_escape_string($post_title);
	$post_content = $mysqli->real_escape_string($post_content);
	$markdown     = $mysqli->real_escape_string($markdown);
	$header_image = $mysqli->real_escape_string($header_image);

	$title_url = title_to_url($post_title);
	$draft_date = date("Y-m-d H:i:s");
	$author = $_SESSION["user_id"];

	// Build the query
	$query  = "INSERT INTO {$db_post_table} (";
	$query .= "post_title, post_content, markdown_content, header_image, title_url, user_id, draft_date";
	$query .= ") VALUES (";
	$query .= "'{$post_title}', '{$post_content}', '{$markdown}', '{$header_image}', '{$title_url}', '{$author}', '{$draft_date}')";

	// Execute the query
	$mysqli->query($query);
}

function save_post($current_post, $post_title, $post_content, $markdown) {
	global $mysqli;
	global $db_post_table;

	$post_content = htmlentities($post_content);

	// Escape all strings
	$post_title   = $mysqli->real_escape_string($post_title);
	$post_content = $mysqli->real_escape_string($post_content);
	$markdown     = $mysqli->real_escape_string($markdown);

	$title_url = title_to_url($post_title);
	$draft_date = date("Y-m-d H:i:s");

	// Build the query
	$query  = "UPDATE {$db_post_table} ";
	$query .= "SET post_title = '{$post_title}', ";
	$query .= "post_content = '{$post_content}', ";
	$query .= "markdown_content = '{$markdown}', ";
	$query .= "title_url = '{$title_url}', ";
	$query .= "draft_date = '{$draft_date}' ";
	$query .= "WHERE id = '{$current_post}'";

	// Execute the query
	$mysqli->query($query);
}

function publish_post($current_post) {
	global $mysqli;
	global $db_post_table;

	$publish_date = date("Y-m-d H:i:s");

	$query  = "UPDATE {$db_post_table} ";
	$query .= "SET publish_date = '{$publish_date}', ";
	$query .= "published = '1' WHERE id = '{$current_post}'";

	// Execute the query
	$mysqli->query($query);
}

function unpublish($post_id) {
	global $mysqli;
	global $db_post_table;

	$mysqli->query("UPDATE {$db_post_table} SET published = '0' WHERE id = '{$post_id}'");
}

function published($post_id) {
	global $mysqli;
	global $db_post_table;

	$query = $mysqli->query("SELECT published FROM {$db_post_table} WHERE id = '{$post_id}'");

	// return true if the published field is 1
	while ($row = $query->fetch_assoc()) {
		if ($row["published"] == "1" || $row["published"] == 1) {
			return true;
		}
	}

	return false;
}

function all_published_posts() {
	global $mysqli;
	global $db_post_table;

	$posts = array();

	$query = $mysqli->query("SELECT id FROM {$db_post_table} WHERE published = '1'");

	while ($row = $query->fetch_assoc()) {
		$posts[] = $row["id"];
	}

	return $posts;
}

function post_pagination($page, $return_value) {
	global $mysqli;
	global $db_post_table;

	// Number of posts per page
	$limit = 4;

	// Count the total amount of pages
	$pages = ceil(count(all_published_posts()) / $limit);

	if (isset($page) && !is_null($page)) {
		$start = ($page - 1) * $limit;
		$posts = array();
		if (logged_in()) {
			$query = $mysqli->query("SELECT id FROM {$db_post_table} ORDER BY id DESC LIMIT {$start}, {$limit}");
		} else {
			$query = $mysqli->query("SELECT id FROM {$db_post_table} WHERE published = '1' ORDER BY id DESC LIMIT {$start}, {$limit}");
		}

		while ($row = $query->fetch_assoc()) {
			$posts[] = $row["id"];
		}
	}

	switch ($return_value) {
		case "pages":
			return $pages;
		case "post":
			return $posts;
	}
}

function user($field) {
	global $mysqli;
	global $db_user_table;

	$current_user = $_SESSION["user_id"];

	switch($field) {
		case "first_name":
			$content = $mysqli->query("SELECT first_name FROM {$db_user_table} WHERE id = '{$current_user}'");
			break 1;
		case "last_name":
			$content = $mysqli->query("SELECT last_name FROM {$db_user_table} WHERE id = '{$current_user}'");
			break 1;
		case "username":
			$content = $mysqli->query("SELECT username FROM {$db_user_table} WHERE id = '{$current_user}'");
			break 1;
		case "join_date":
			$content = $mysqli->query("SELECT join_date FROM {$db_user_table} WHERE id = '{$current_user}'");
			break 1;
		default:
			$content = $mysqli->query("SELECT username FROM {$db_user_table} WHERE id = '{$current_user}'");
			break 1;
	}

	$content = $content->fetch_assoc();

	return $content[$field];
}

function last_post($id = "") {
	global $mysqli;
	global $db_post_table;

	$last_id = "";

	$query = $mysqli->query("SELECT id FROM {$db_post_table} ORDER BY id DESC LIMIT 1");

	while ($row = $query->fetch_assoc()) {
		$last_id = $row["id"];
	}

	return $last_id;
}

function post($post_id, $field) {
	global $mysqli;
	global $db_post_table;
	global $db_user_table;

	$query  = "";
	$output = "";

	switch($field) {
		case "title":
			$query = $mysqli->query("SELECT post_title FROM {$db_post_table} WHERE id = '{$post_id}'");
			$field = "post_title";
			break 1;
		case "title_url":
			$query = $mysqli->query("SELECT title_url FROM {$db_post_table} WHERE id = '{$post_id}'");
			break 1;
		case "url":
			$query = $mysqli->query("SELECT title_url FROM {$db_post_table} WHERE id = '{$post_id}'");
			$title_url = $query->fetch_assoc();
			$title_url = $title_url["title_url"];

			return BASE_URL . "/post/{$post_id}/{$title_url}";
		case "edit_url":
			return BASE_URL . "/edit/{$post_id}";
		case "content":
			$query = $mysqli->query("SELECT post_content FROM {$db_post_table} WHERE id = '{$post_id}'");

			while ($row = $query->fetch_assoc()) {
				$content = $row["post_content"];
			}

			return html_entity_decode($content);
		case "short_content":
			$query = $mysqli->query("SELECT post_content FROM {$db_post_table} WHERE id = '{$post_id}'");

			while ($row = $query->fetch_assoc()) {
				$content = $row["post_content"];
			}

			$content = html_entity_decode($content);
			$start = 0;
			$first_tag = substr($content, 0, 3);

			if ($first_tag != "<p>") {
				$start = strpos($content, "<p>") - 1;
			}

			$limit = 390;

			$short_content = substr($content, $start, $limit);

			return $short_content . "...";
		case "markdown":
			$query = $mysqli->query("SELECT markdown_content FROM {$db_post_table} WHERE id = '{$post_id}'");
			$field = "markdown_content";
			break 1;
		case "header_image":
			$query = $mysqli->query("SELECT header_image FROM {$db_post_table} WHERE id = '{$post_id}'");
			break 1;
		case "publish_date":
			$query = $mysqli->query("SELECT publish_date FROM {$db_post_table} WHERE id = '{$post_id}'");

			$date = $query->fetch_assoc();
			$date = strtotime($date["publish_date"]);

			return date("n/j/Y", $date);
		case "draft_date":
			$query = $mysqli->query("SELECT draft_date FROM {$db_post_table} WHERE id = '{$post_id}'");

			$date = $query->fetch_assoc();
			$date = strtotime($date["draft_date"]);

			return date("m/d/Y", $date);
		case "author":
			$query = $mysqli->query("SELECT user_id FROM {$db_post_table} WHERE id = '{$post_id}'");
			$user_id = $query->fetch_assoc();
			$user_id = $user_id["user_id"];

			$query = $mysqli->query("SELECT first_name, last_name FROM {$db_user_table} WHERE id = '{$user_id}'");

			while ($row = $query->fetch_assoc()) {
				$first_name = $row["first_name"];
				$last_name  = $row["last_name"];
			}

			return $first_name . " " . $last_name;
		default:
			$query = $mysqli->query("SELECT post_title FROM {$db_post_table} WHERE id = '{$post_id}'");
			break 1;
	}

	while ($row = $query->fetch_assoc()) {
		$output = $row[$field];
	}

	return $output;
}

function upload_image($post_id, $file = array()) {
	global $mysqli;
	global $db_post_table;
	global $max_header_size;
	global $header_exts;

	if (isset($file)) {
		$upload_dir = "../assets/images/";

		$temp = explode(".", $file["name"]);
		$extension = end($temp);

		$filename = uniqid('header_') . "." . $extension;

		// Build the target path
		$target_path = $upload_dir . $filename;
		$image_path = "http:" . BASE_URL . "/public/assets/images/" . $filename;

		if ($file["size"] <= size($max_header_size)) {
			if (in_array($extension, $header_exts)) {
				if (move_uploaded_file($file['tmp_name'], $target_path)) {
					$mysqli->query("UPDATE {$db_post_table} SET header_image = '{$image_path}' WHERE id = '{$post_id}'");
					return $image_path;
				}
			}
		}
	}


}

?>
