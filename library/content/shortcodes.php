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
