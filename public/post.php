<?php
/**
 *	File: public/post.php
 *	Desc: Displays a single entire article
 *	Author: Jaden Dessureault
 */
?>

<?php require_once("../includes/init.php"); ?>

<?php $id = $_GET["post"]; ?>
<?php
	if (!logged_in()) {
		if (!published($id)) redirect_to(BASE_URL);
	}
?>

<?php $title = post($id, "title"); ?>

<?php include_once(layout("header.php")); ?>

<header class="post-header" style="background:linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url(<?php echo post($id, "header_image"); ?>);">
	<div class="wrapper">
		<a class="no-underline" href="<?php echo BASE_URL; ?>"><div class="post-logo-small"></div></a>
		<div class="post-info">
			<h2 class="post-title"><a href="<?php echo post($id, "url"); ?>" class="no-underline"><?php echo post($id, "title"); ?></a></h2>
			<div class="post-author">by <a href="#" class="no-underline"><?php echo post($id, "author"); ?></a> on <span class="post-date"><?php echo published($id) ? post($id, "publish_date") : post($id, "draft_date"); ?></span>.</div>
		</div>
		<?php if (logged_in()) { ?>
			<div class="current-post-edit">
				<a href="<?php echo post($id, "edit_url"); ?>" class="no-underline"><i class="icon-settings"></i></a>
			</div>
		<?php } ?>
	</div>
</header>
<div class="clearfix wrapper" style="height: auto;">
	<article class="post-long clearfix">
		<?php echo post($id, "content"); ?>
	</article>
</div>

<?php include_once(layout("footer.php")); ?>