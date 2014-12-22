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