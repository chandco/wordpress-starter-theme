<?php

add_action('wp_ajax_custom_mce', 'wp_custom_mce');

function wp_custom_mce() {
	include("edit-feature-box.php");
}



// create a page for it to work on






//class mcea {

//	function __construct() {
		add_action( 'admin_menu', 'mcea_admin_init' ); 


function mce_wp_enqueue_media($hook) {



    wp_enqueue_script('jquery');
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'mce_wp_enqueue_media' );


//	}

	function mcea_admin_init() {
		add_submenu_page( null, 'Edit Feature Box', 'Edit Feature Box', 'upload_files', 'feature-box-edit', 'feature_box_page' );

	}


	function feature_box_page() {

		

		//        wp_enqueue_script('wptuts-upload');
		
	

		global $imagesizes;
		$suffix = "-" . $imagesizes["gallery-thumb"][0] . "x" . $imagesizes["gallery-thumb"][1];
		
		?>

		<style>	

		.uploader img {
			max-width: <?php echo $imagesizes["gallery-thumb"][0]; ?>px;
			width: 100%;
			height: auto;
		}

		#wpadminbar, #adminmenuwrap, #adminmenuback {
			display: none;
		}

		.media-modal {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			z-index: 160000;
		}



		</style>
		<script language="JavaScript">
		

// Uploading files
var file_frame;

jQuery(document).ready(function($){

  $('.button').on('click', function( event ) {



    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      file_frame.open();
      return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: jQuery( this ).data( 'uploader_title' ),
      button: {
        text: jQuery( this ).data( 'uploader_button_text' ),
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();
      console.log(attachment);

      $('#_unique_name').val(attachment.url);
      $(".uploader img").remove();
      ext = attachment.url.substr(attachment.url.length - 4);
      thumb = attachment.url.replace(ext, '<?php echo $suffix; ?>' + ext);
	  $("<img>").attr("src", thumb).insertAfter( '#_unique_name_button' );


      // Do something with attachment.id and/or attachment.url here
    });

    // Finally, open the modal
    file_frame.open();
  });

});

		</script>

		

		<form id='mce-feature-box-form'>

		<div class="uploader">
			<label>Image For Box<input id="_unique_name" name="settings[_unique_name]" type="text" /></label>
			<input id="_unique_name_button" class="button" name="_unique_name_button" type="submit" value="Upload" />
		</div>

		<label>
			Box Title
			<input type='text' name='mce-feature-box' id='mce-feature-box' />
		</label>

		<label>
			Box Text (Optional)
			<textarea id='mce-feature-content'></textarea>
		</label>

		<input type='submit' value='Update' />
	</form>

	<script>
	var dialogArguments = parent.tinymce.activeEditor.windowManager.getParams();


	console.log(dialogArguments);

	document.getElementById('mce-feature-box').value = "myVAlue";//dialogArguments.title;
	//document.getElementById('mce-feature-content').value = dialogArguments.content;
	//document.getElementById('_unique_name').value = dialogArguments.imgData.url;

	document.getElementById('mce-feature-box-form').addEventListener('submit', function(e) {

		console.log('submitted');


		e.preventDefault();

		console.log(parent.tinymce.activeEditor);

		parent.wp.mce.boutique_banner.update( document.getElementById('mce-feature-box').value );
		
		/*

		parent.tinymce.activeEditor.plugins.features._process_popup_form(
			document.getElementById('mce-feature-box').value,
			document.getElementById('mce-feature-content').value,
			document.getElementById('_unique_name').value,
			parent.tinymce.activeEditor.windowManager,
			dialogArguments.element
		);

		*/

	})


	</script>
<?php
		 
		   
		       
		 
		   

		 		



	}
//}
//$menu = new mcea();

?>