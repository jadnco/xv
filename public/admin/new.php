<?php
/**
 *	File: public/new.php
 *	Desc: Displays editor for creating a new post
 *	Author: Jaden Dessureault
 */
?>

<?php $title = "New Post"; ?>

<?php require_once("../../includes/init.php"); ?>

<?php if (!logged_in()) redirect_to(BASE_URL . "/login"); ?>

<?php include_once(layout("header.php")); ?>
<?php include_once(layout("new_save_modal.php")); ?>

<div class="half-full markdown-editor new-post">
	<div class="new-post-header"><div class="section-text">Markdown</div></div>
	<textarea class="text-wrap" spellcheck="false" placeholder="Start writing some markdown" autofocus></textarea>
	<div class="new-post-footer">
		<a class="editor-icon-link new-markdown tooltip-left" data-title="What is Markdown?" href="http://daringfireball.net/projects/markdown/" target="_blank"><span class="icon-info"></span></a>
	</div>
</div>
<div class="half-full post-preview new-post">
	<div class="new-post-header"><div class="section-text">Preview</div></div>
	<div class="text-wrap"></div>
	<div class="new-post-footer">
		<a class="editor-icon-link tooltip-left" data-title="Save draft" href="javascript:void(0);"><span class="icon-reload"></span></a>
		<input type="text" class="editor-title" placeholder="Blog Title" value="">
	</div>
</div>

<?php include_once(layout("footer.php")); ?>