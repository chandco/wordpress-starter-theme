<?php

/* this is the start of all the features needed for updating the TinyMCE editor.

	Some parts will be specific to the theme as 
	they'll deal with specifically styled shortcodes 
	but hopefully we can keep their markup and 
	naming generic and only the styles would change

	Shortcodes and their styles:

	1. feature-box // for an image / title / text / link thing.  CSS can change the behaviour
		# TODO LIST:
			# Decide on Markup
			# Form for inputting the data
			# process variables into shortcode atts
			# CSS for components





	2. image-cta // CTA which is an image with a text overlay.  Kinda like feature box without the text and forces a link

	3. CTA button // chunky button, with optional icon

	4. column system.  we should not use views for this due to nesting





*/



// not really a shortcode but it is for the editor
// // Callback function to insert 'styleselect' into the $buttons array
function my_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'my_mce_buttons_2');

// Callback function to filter the MCE settings
## This is currently an example, we're not using this yet as we may want to stick with views for now.

function my_mce_before_init_insert_formats( $init_array ) {  
	// Define the style_formats array
	$style_formats = array(  
		// Each array child is a format with it's own settings
		array(  
			'title' => 'Call To Action',  
			'selector' => 'a',  
			'classes' => 'cta-button',
			'wrapper' => false,
			
		),  
		
		array(  
			'title' => 'New Line',  
			'block' => 'div',  
			'classes' => 'cf newline',
			'wrapper' => true,
		),

		array()
	);  
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );  
	
	return $init_array;  
  
} 
// Attach callback to 'tiny_mce_before_init' 
//add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );


add_action( 'init', 'cf_editor_buttons' );
function cf_editor_buttons() {
    add_filter( "mce_external_plugins", "wptuts_add_buttons" );
    add_filter( 'mce_buttons', 'wptuts_register_buttons' );
}
function wptuts_add_buttons( $plugin_array ) {
    $plugin_array['cf_features'] = get_stylesheet_directory_uri() . '/admin/js/tinymce.js';
    return $plugin_array;
}




function wptuts_register_buttons( $buttons ) {

	// update this after the javascript is done
    array_push( $buttons, 'feature', 'halves', 'thirds', 'twothirds-third', 'third-twothirds', 'quarters' ); // dropcap', 'recentposts
    return $buttons;
}


add_filter('mce_external_plugins', 'tinymce_core_plugins');




function tinymce_core_plugins () {
     $plugins = array('noneditable'); //Add any more plugins you want to load here
     $plugins_array = array();

     //Build the response - the key is the plugin name, value is the URL to the plugin JS
     foreach ($plugins as $plugin ) {
          $plugins_array[ $plugin ] = get_stylesheet_directory_uri() . '/library/js/tinymce/' . $plugin . '/plugin.min.js';
     }
     return $plugins_array;
}


// PHP handling of various ajaxy shortcode stuff:
// setup media library
add_action( 'admin_enqueue_scripts', 'mce_wp_enqueue_media' );
function mce_wp_enqueue_media($hook) {
	
	
	wp_enqueue_style( 'admin-helper-css', get_stylesheet_directory_uri() . '/library/css/admin.css' );
	if ($hook != 'admin_page_' . 'feature-box-edit') return;

    wp_enqueue_script('jquery');
    wp_enqueue_script( 'wplink' );
    wp_enqueue_script('wpdialogs');
    wp_enqueue_script('wpdialogs-popup'); //also might need this

	// need these styles
	wp_enqueue_style('wp-jquery-ui-dialog');
	wp_enqueue_style('thickbox');

    wp_enqueue_media();
}


require_once("mce_feature-box.php");

add_action( 'before_wp_tiny_mce', 'custom_before_wp_tiny_mce' );

function custom_before_wp_tiny_mce() {

	global $imagesizes;
	$suffix = "-" . $imagesizes["gallery-thumb"][0] . "x" . $imagesizes["gallery-thumb"][1];

	?>
	<script type="text/javascript">
    
    

    window.mcedata = { 
    	adminurl : '<?php echo get_admin_url(); ?>',
    	siteurl : '<?php echo get_site_url(); ?>',
    	apiURL : '<?php echo get_site_url("wp-json"); ?>/wp-json/',
    	imgSuffix : '<?php echo $suffix; ?>',
    	};

	</script>


	<?php


	// load all the views here
	views_feature_box(); // [feature-box]

	#	[wide_background]

	#	[CTA]

	#	

	#	[carousel]

	#	[columns]


	## LATER ##
	#	CF7

	// Insert normal HTML with classes:

	# Bulleted List with special bullets
	# 


	// etc

}

// ajaxes for tinymce


