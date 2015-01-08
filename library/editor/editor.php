<?php

/* this is the start of all the features needed for updating the TinyMCE editor.

	Some parts will be specific to the theme as 
	they'll deal with specifically styled shortcodes 
	but hopefully we can keep their markup and 
	naming generic and only the styles would change

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
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );


add_action( 'init', 'cf_editor_buttons' );
function cf_editor_buttons() {
    add_filter( "mce_external_plugins", "wptuts_add_buttons" );
    add_filter( 'mce_buttons', 'wptuts_register_buttons' );
}
function wptuts_add_buttons( $plugin_array ) {
    $plugin_array['features'] = get_stylesheet_directory_uri() . '/admin/js/tinymce.js';
    return $plugin_array;
}




function wptuts_register_buttons( $buttons ) {
    array_push( $buttons, 'feature', 'halves', 'thirds', 'twothirds-third', 'third-twothirds', 'quarters' ); // dropcap', 'recentposts
    return $buttons;
}


add_filter('mce_external_plugins', 'tinymce_core_plugins');

add_action( 'before_wp_tiny_mce', 'custom_before_wp_tiny_mce' );
function custom_before_wp_tiny_mce() {

	// manual localisation before I find a better way.
    echo( '<script type="text/javascript">' );
    echo 'window.mcedata = { adminurl : "' . get_admin_url() . '" };';
	echo '</script>';
	?>
		<script type="text/html" id="tmpl-editor-boutique-banner">
			<div class="boutique_banner_{{ data.type }}"> Aww Yiss</div>
	        <div class="full_banner" id="banner_{{ data.id }}">
			    <span class="title">{{ data.title }}</span>
			    <span class="content">{{ data.innercontent }}</span>
		        <# if ( data.link ) { #>
		            <# if ( data.linkhref ) { #>
			            <a href="{{ data.linkhref }}" class="link dtbaker_button_light">{{ data.link }} </a>
					<# } #>
				<# } #>
			</div>
		</script>

	<?php
}


function tinymce_core_plugins () {
     $plugins = array('noneditable'); //Add any more plugins you want to load here
     $plugins_array = array();

     //Build the response - the key is the plugin name, value is the URL to the plugin JS
     foreach ($plugins as $plugin ) {
          $plugins_array[ $plugin ] = get_stylesheet_directory_uri() . '/library/js/tinymce/' . $plugin . '/plugin.min.js';
     }
     return $plugins_array;
}


// ajax for tinymce
require_once("mce_ajax.php");

