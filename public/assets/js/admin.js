/**
 *	File: public/assets/config.php
 *	Desc: All the front-end js, ajax
 *	Author: Jaden Dessureault
 */
 
$(document).ready(function() {
	$(".markdown-editor .text-wrap").crevasse({
		previewer: $(".post-preview .text-wrap"),
		editorStyle: "markdown", // theme to use for editor
		usePreviewerReset: false, // reset CSS for previewer pane
		previewerStyle: "", // theme to use for previewer
		convertTabsToSpaces: 2 // number of spaces or false
	});

	$(".new-post.post-preview a[data-title='Save draft']").on("click", new_post);
	$(".edit-post.post-preview a[data-title='Save draft']").on("click", save_post);
	$(".edit-post.post-preview a[data-title='Publish post']").on("click", publish_post);
	$(".photo-modal #header-form .upload-btn").on("click", function() {
		$(".photo-modal #header-form").submit();
	});

	$(".new-post.post-preview a[data-title='Upload header image']").on("click", function() {
		$(".photo-modal").addClass("md-show");
	});

	$(".photo-modal #header-form input[type='file']").change(function() {
		$(this).hide();
		$(".photo-modal #header-form .upload-btn").show();
	});

	// Command+s to save draft
	$(window).keypress(function(event) {
	    if (event.which == 115 && (event.ctrlKey||event.metaKey) || (event.which == 19)) {
	        event.preventDefault();

	        save_post();

	        return false;
	    }
	    return true;
    });

	function new_post() {
		var postTitle    = $(".editor-title").val(),
			postContent  = $(".new-post.post-preview .text-wrap .crevasse_previewer").html(),
			postMarkdown = $(".new-post.markdown-editor textarea.text-wrap").val(),
			headerImage  = "";

		$.ajax({
		    url: "admin/create.php",
		    method: "POST",
		    data: {
				post_title: postTitle,
				post_content: postContent,
				post_markdown: postMarkdown,
				header_image: headerImage
			},
			beforeSend: function() {
				$(".new-save-modal").addClass("md-show");
			},
		    success: function(data) {
		        window.location.replace("../edit/" + data);
		    }
		});
	}

	function save_post() {
		var postTitle    = $(".editor-title").val(),
			postContent  = $(".edit-post.post-preview .text-wrap .crevasse_previewer").html(),
			postMarkdown = $(".edit-post.markdown-editor textarea.text-wrap").val(),
			postId       = $(".edit-post.post-preview a[data-title='Save draft']").attr("data-post"),
			editScript   = "admin/save.php?post=" + postId;

		$.ajax({
		    url: editScript,
		    method: "POST",
		    data: {
		    	post_id: postId,
				post_title: postTitle,
				post_content: postContent,
				post_markdown: postMarkdown
			},
			beforeSend: function() {
				$(".edit-save-modal").addClass("md-show");
			},
		    success: function(data) {
		        $(".edit-save-modal").removeClass("md-show");
		    }
		});
	}

	function publish_post() {
		var postId        = $(".edit-post.post-preview a[data-title='Save draft']").attr("data-post"),
			postTitle     = $(".editor-title").val(),
			postContent   = $(".edit-post.post-preview .text-wrap .crevasse_previewer").html(),
			postMarkdown  = $(".edit-post.markdown-editor textarea.text-wrap").val(),
			publishScript = "admin/publish.php";

		$.ajax({
		    url: publishScript,
		    method: "POST",
		    data: {
				post_id: postId,
				post_title: postTitle,
				post_content: postContent,
				post_markdown: postMarkdown
			},
			beforeSend: function() {
				$(".publish-modal").addClass("md-show");
			},
		    success: function(data) {
		        window.location.replace("../post/" + data);
		    }
		});
	}

	var percentBar = $(".photo-modal .upload-percent-bar"),
		uploadPercent = $(".photo-modal .upload-btn span"),
		postUrl = "admin/upload_header.php?post=" + $(".edit-post.post-preview a[data-title='Upload header image']").attr("data-post");

	$(".photo-modal #header-form").ajaxForm({
			url: postUrl,
			beforeSend: function() {
			    var percentVal = '0%';
			    percentBar.width(percentVal)
			    uploadPercent.text("Upload");
			},
			uploadProgress: function(event, position, total, percentComplete) {
			    var percentVal = percentComplete + '%';
			    percentBar.width(percentVal)
			    uploadPercent.text(percentVal);
			},
			success: function(xhr, statusText, data) {
			    var percentVal = '100%';
			    percentBar.width(percentVal);
			    uploadPercent.text(percentVal);
			    $(".photo-modal").removeClass("md-show");
			    $(".photo-modal #header-form input[type='file']").hide();
			    $(".photo-modal #header-form .upload-btn").hide();
			    percentBar.hide();
			    uploadPercent.text("Upload");

			}
		});
});