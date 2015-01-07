<?php


function cf_wide_background($atts, $content) {


	 $atts = shortcode_atts( array(
        'class' => '',
        'id' => '',
    ), $atts );


	
	$output = "<div class='full-width-background " . $atts["class"] . "'";

	$output .= ($atts["id"]) ? " id='" . $atts["id"] . "'>" : ">";

	$output .= $content;	
	$output .= "</div>";
	

	return $output;
}
add_shortcode("wide_background", "cf_wide_background");


function cf_popup_content($atts, $content) {

	/* 
			
	1 PHP:
	1.1 wrap around in a div with special class
	1.2 do shortcodes inside
	
	[popupwindow text='Click me' element='a' class='classname' id='id' 'attributes'='']
	*/

	// we may need to add an ID to remember the originating box in javascript.  Let's see if not though.

	$atts = shortcode_atts( array(
			    'text' => 'More Information', // not really optional but let's not srew up thigns
			    'element' => 'a',
			    'attributes' => false,
			    'id' => false,
			    'class' => false,
			), $atts );

	$opening_chunk = "<";
	if ($atts["element"] == "a" && $atts["attributes"] == false) {
		$opening_chunk .= "a href='#' ";
	} else {
		$opening_chunk .= $atts["element"] . " " . $atts["attributes"] . " ";
	}

	if ($atts["class"]) {
		$opening_chunk .= "class='popup-button " . $atts["class"] . "' ";
	} else {
		$opening_chunk .= "class='popup-button' ";
	}

	if ($atts["id"]) { $opening_chunk .= "id='" . $atts["id"] . "'"; }


	$opening_chunk .= ">" . $atts["text"] . "</" . $atts["element"] . ">";
	
	return "<div class='cf-popup-box'>" . $opening_chunk . "<div class='popup mfp-hide'>" . do_shortcode( $content ) . "</div></div>";
}

add_shortcode( 'popupwindow', 'cf_popup_content' );


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

