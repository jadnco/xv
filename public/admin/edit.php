<?php
/**
 *	File: public/edit.php
 *	Desc: Displays editor for editing an already created post
 *	Author: Jaden Dessureault
 */
?>
<?php $title = "Edit Post"; ?>

<?php require_once("../../includes/init.php"); ?>

<?php if (!logged_in()) redirect_to(BASE_URL . "/login"); ?>

<?php if (!isset($_GET["post"])) redirect_to(BASE_URL); ?>

<?php $id = $_GET["post"]; ?>

<?php include_once(layout("header.php")); ?>
<?php include_once(layout("photo_modal.php")); ?>
<?php include_once(layout("edit_save_modal.php")); ?>
<?php include_once(layout("publish_modal.php")); ?>

<div class="half-full markdown-editor edit-post">
	<div class="new-post-header"><div class="section-text">Markdown</div></div>
	<textarea class="text-wrap" spellcheck="false" placeholder="Start writing some markdown" autofocus><?php echo post($id, "markdown"); ?></textarea>
	<div class="new-post-footer">
		<a class="editor-icon-link info-markdown tooltip-left" data-title="What is Markdown?" href="http://daringfireball.net/projects/markdown/" target="_blank"><span class="icon-info"></span></a>
		<a class="editor-icon-link home-markdown tooltip-right" data-title="Bring me home" href="<?php echo BASE_URL; ?>"><span class="icon-home"></span></a>
	</div>
</div>
<div class="half-full post-preview edit-post">
	<div class="new-post-header"><div class="section-text">Preview</div><a href="../logout" class="logout no-underline">Logout</a></div>
	<div class="text-wrap"></div>
	<div class="new-post-footer">
		<a class="editor-icon-link tooltip-left md-trigger" data-modal="modal-1" data-post="<?php echo $id; ?>" data-title="Upload header image" href="javascript:void(0);"><span class="icon-camera"></span></a>
		<a class="editor-icon-link tooltip-left" data-modal="modal-1" data-title="Save draft" data-post="<?php echo $id; ?>" href="javascript:void(0);"><span class="icon-reload"></span></a>
		<a class="editor-icon-link eye-icon tooltip-left" data-title="Preview post" href="<?php echo post($id, "url"); ?>"><span class="icon-eye"></span></a>
		<input type="text" class="editor-title" placeholder="Blog Title" value="<?php echo post($id, "title"); ?>">
		<?php if (published($id)) { ?>
			<a href="admin/unpublish.php?post=<?php echo $id; ?>" data-title="Unpublish post" class="no-underline trash-icon editor-icon-link tooltip-right"><i class="icon-trash"></i></a>
		<?php } ?>
		<a class="editor-icon-link cloud-icon tooltip-right" data-title="Publish post" href="javascript:void(0);"><span class="icon-cloud-upload"></span></a>
	</div>
</div>

<?php include_once(layout("footer.php")); ?>