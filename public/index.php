<?php
/**
 *	File: public/index.php
 *	Desc: Displays all latest articles
 *	Author: Jaden Dessureault
 */
?>
<?php $title = "Home"; ?>

<?php require_once("../includes/init.php"); ?>

<?php $page = isset($_GET["page"]) ? $_GET["page"] : 1; ?>

<?php if ($page > post_pagination(null, "pages") || $page == 0) redirect_to(BASE_URL); ?>

<?php include_once(layout("header.php")); ?>

<div class="wrapper clearfix">
	<header id="main-header">
		<a href="<?php echo BASE_URL; ?>" title="Home"><div class="header-logo"></div></a>
	</header>

	<?php foreach (post_pagination($page, "post") as $post => $id) { ?>
		<article class="post-short clearfix<?php if (!published($id)) echo " unpublished"; ?>">
			<a href="<?php echo post($id, "url");?>"><div style="background-image: url(<?php echo post($id, "header_image"); ?>);" class="post-main-image"></div></a>
			<?php if (logged_in()) { ?>
				<a href="<?php echo post($id, "edit_url"); ?>" class="edit-link no-underline" title="Edit Post"><i class="icon-settings"></i></a>
				<?php if (published($id)) { ?>
					<a href="admin/unpublish.php?post=<?php echo $id; ?>" class="edit-link no-underline" title="Unpublish Post"><i class="icon-cloud-download"></i></a>
				<?php } ?>
			<?php } ?>
			<h2 class="post-title"><a class="no-underline" href="<?php echo post($id, "url"); ?>"><?php echo post($id, "title"); ?></a></h2>
			<div class="post-author">by <a class="no-underline" href="javascript:void(0);"><?php echo post($id, "author"); ?></a> on <?php echo published($id) ? post($id, "publish_date") : post($id, "draft_date"); ?>.</div>
			<div class="post-content clearfix"><?php echo post($id, "short_content"); ?></div>
			<a class="post-read-more no-underline" href="<?php echo post($id, "url"); ?>">Read more</a>
		</article>
		<div class="post-short-divide"><ul><li></li><li></li><li></li></ul></div>
	<?php } ?>

	<?php if (post_pagination(null, "pages") > 0) { ?>
		<div class="pagination-links">
			<ul>
				<?php for ($i = 1; $i <= post_pagination(null, "pages"); $i++) { ?>
					<li class="<?php if ($page == $i) echo "current"; ?>"><a href="<?php echo BASE_URL . "/page/" . $i; ?>" class="no-underline"><?php echo $i; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>

</div>

<?php include_once(layout("footer.php")); ?>
