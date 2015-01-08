<?php


	add_action( 'admin_menu', 'mcea_feature_box_init' ); 






	function mcea_feature_box_init() {
		add_submenu_page( null, 'Edit Feature Box', 'Edit Feature Box', 'upload_files', 'feature-box-edit', 'feature_box_page' );
	}


	function feature_box_page() {

		// we might want to reuse this and use some criteria i.e. hiding things.



		

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

			$('body').on('click', '#mce-addlink', function(event) {
				event.preventDefault();
	            wpActiveEditor = true; //we need to override this var as the link dialogue is expecting an actual wp_editor instance
	            
	            url = $('#_feature_box_link').val();
	        	title = $('#_feature_box_title').val();

	        	

	        	wpLink.setDefaultValues = function () { 
			        $('#url-field').val(url);
	        		$('#link-title-field').val(title);
			        $('#wp-link-submit').val( 'Use this link' );
			    };

	            wpLink.open(); //open the link popup
	            return false;
	        });


	        $('body').on('click', '#wp-link-cancel a, #wp-link-close', function(e) {
	        	e.preventDefault();
	        	      
	            wplinkCloseManual();
	           

	        });

	        function wplinkCloseManual() {
	        	$( document.body ).removeClass( 'modal-open' );
	        	$('#_feature_box_link').focus();
				$('#wp-link-wrap').hide();
				$( '#wp-link-backdrop' ).hide();
	        }


	        $('body').on('click', '#wp-link-update .button', function(e) {
	        	e.preventDefault();

	        	url = $('#url-field').val();
	        	
	        	title = $('#link-title-field').val();

	        	$('#_feature_box_link').val(url);
	        	$('#_feature_box_title').val(title);

	        	wplinkCloseManual();
	        	


	        	


	        });


		  $('#mce-upload').on('click', function( event ) {



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
		      $("#mce-feature-box-form .feature-box img").remove();
		      ext = attachment.url.substr(attachment.url.length - 4);
		      thumb = attachment.url.replace(ext, '<?php echo $suffix; ?>' + ext);
			  $("<img>").attr("src", thumb).prependTo( '#mce-feature-box-form .feature-box .img' );


		      // Do something with attachment.id and/or attachment.url here
		    });

		    // Finally, open the modal
		    file_frame.open();
		  });

		});

		</script>

		
			<div id="wp-link-backdrop" style="display: none"></div>
		<div id="wp-link-wrap" class="wp-core-ui search-panel-visible" style="display: none">
		<form id="wp-link" tabindex="-1">
		<input type="hidden" id="_ajax_linking_nonce" name="_ajax_linking_nonce" value="4223259a55" />		<div id="link-modal-title">
			Insert/edit link			<button type="button" id="wp-link-close"><span class="screen-reader-text">Close</span></button>
	 	</div>
		<div id="link-selector">
			<div id="link-options">
				<p class="howto">Enter the destination URL</p>
				<div>
					<label><span>URL</span><input id="url-field" type="text" name="href" /></label>
				</div>
				<div>
					<label><span>Title</span><input id="link-title-field" type="text" name="linktitle" /></label>
				</div>
				<div class="link-target">
					<label><span>&nbsp;</span><input type="checkbox" id="link-target-checkbox" /> Open link in a new window/tab</label>
				</div>
			</div>
			<p class="howto"><a href="#" id="wp-link-search-toggle">Or link to existing content</a></p>
			<div id="search-panel">
				<div class="link-search-wrapper">
					<label>
						<span class="search-label">Search</span>
						<input type="search" id="search-field" class="link-search-field" autocomplete="off" />
						<span class="spinner"></span>
					</label>
				</div>
				<div id="search-results" class="query-results" tabindex="0">
					<ul></ul>
					<div class="river-waiting">
						<span class="spinner"></span>
					</div>
				</div>
				<div id="most-recent-results" class="query-results" tabindex="0">
					<div class="query-notice" id="query-notice-message">
						<em class="query-notice-default">No search term specified. Showing recent items.</em>
						<em class="query-notice-hint screen-reader-text">Search or use up and down arrow keys to select an item.</em>
					</div>
					<ul></ul>
					<div class="river-waiting">
						<span class="spinner"></span>
					</div>
				</div>
			</div>
		</div>
		<div class="submitbox">
			<div id="wp-link-cancel">
				<a class="submitdelete deletion" href="#">Cancel</a>
			</div>
			<div id="wp-link-update">
				<input type="submit" value="Add Link" class="button button-primary" id="wp-link-submit" name="wp-link-submit">
			</div>
		</div>
		</form>
		</div>

		<form id='mce-feature-box-form'>

		<label>Turn Feature Box into a link (leave blank if you do not want this to link somewhere):</label><BR>
		<button class='button' id='mce-addlink'>Find Link on Site</button><Br />
		URL:
		<input id="_feature_box_link" name="settings[_feature_box_link]" type="text" value='http://' /><BR />
		Title:
		<input id="_feature_box_title" name="settings[_feature_box_title]" type="text" />
		<hr>
		<div class='feature-box'>

			<header>
				<div class="img">
					<input id="_unique_name" name="settings[_unique_name]" type="text" />
					<button id='mce-upload' class='button'>Upload New Image</button>
				</div>

				<div class='h2'>
					<label>Title
						<input type='text' name='mce-feature-box' id='mce-feature-box' />
					</label>
				</div>
			
			</header>

			<label>
				Box Text (Optional)
				<textarea id='mce-feature-content'></textarea>
			</label>
		</div>

		<button id='mce-update' class='button'>Update</button> <button id='mce-close' class='button'>Close without Updating</button>
	</form>

	<script>
	
	jQuery(document).ready(function($){

		atts = parent.tinymce.activeEditor.windowManager.getParams();

	 	$('#mce-feature-content').val( atts.data.innercontent );
		$('#mce-feature-box-form header .img').prepend( $("<img src='" + atts.data.img + "' />") );
		$('#mce-feature-box').val( atts.data.title );
		$('#_feature_box_link').val( atts.data.link );

		$('#mce-update').on('click', function(e) {
			e.preventDefault();

			data = {
				innercontent : $('#mce-feature-content').val(),
				img: $('#mce-feature-box-form header img').attr('src'),
				title : $('#mce-feature-box').val(),
				link: $('#_feature_box_link').val(),
			}
			parent.wp.mce.feature_box.update( data );

			parent.tinymce.activeEditor.windowManager.close();


		})	

		
	
	

	

	

	});


	</script>
<?php
		 
		   
		       
		 
		   

		 		



	}
//}
//$menu = new mcea();

?>