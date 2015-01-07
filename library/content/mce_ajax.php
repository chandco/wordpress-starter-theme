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
		
	


		
		?>

		<style>	

		.uploader img {
			max-width: 400px;
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
			jQuery(document).ready(function($){

				

				




				var _custom_media = true,
				_orig_send_attachment = wp.media.editor.send.attachment;

				$('a.button.media-button.button-primary.button-large.media-button-insert').html("Add Image");
			 
				$('.uploader .button').click(function(e) {
					var send_attachment_bkp = wp.media.editor.send.attachment;
					var button = $(this);
					var id = button.attr('id').replace('_button', '');
					_custom_media = true;
					wp.media.editor.send.attachment = function(props, attachment){
						
						console.log(props);
						console.log(attachment);
						$("#"+id).val(attachment.url);
						

						console.log(attachment.url);
						$(".uploader img").remove();
						$("<img>").attr("src", attachment.url).insertAfter( '#_unique_name_button' );
						console.log(wp.media.editor.insert);
						
					}
					
			 
					wp.media.editor.open(button);
					return false;
				});
			 
				



			});
		</script>

		<div class="uploader">
			<input id="_unique_name" name="settings[_unique_name]" type="text" />
			<input id="_unique_name_button" class="button" name="_unique_name_button" type="text" value="Upload" />
		</div>
<?php
		 
		   
		       
		 
		   

		 		



	}
//}
//$menu = new mcea();

?>