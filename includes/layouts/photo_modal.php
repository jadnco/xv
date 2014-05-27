<div class="md-modal md-effect-1 photo-modal" id="modal-1">
    <div class="md-content">
    	<div class="photo-preview" style="background:linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,1)), url(<?php echo post($id, "header_image"); ?>);">
	    	<i class="icon-close md-close"></i>
	    	<span class="close-text">Close</span>
	    	<i class="icon-camera"></i>
	    	<span class="camera-desc">Upload header image</span>
    	</div>
        <form id="header-form" class="clearfix" action="" method="post" enctype="multipart/form-data">
        	<div class="upload-wrap">
				<input type="file" name="header_image" class="choose-btn">
				<div class="upload-btn"><i class="icon-cloud-upload"></i><span>Upload</span></div>
        	</div>
        </form>
        <div class="upload-percent-bar"></div>
    </div>
</div>