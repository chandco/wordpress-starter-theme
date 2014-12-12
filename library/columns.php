<?php

/* Creates a Grid system.  

	Basic version:

	[columns]

		cONTENT

	{half}

		cONTENT	

	[/columns]

	But with more dividers.  Final column is 'leftover space' so it's up tot he user to understand maths!

*/


function cf_column_system($atts, $content) {


	 $atts = shortcode_atts( array(
        'class' => '',
        'id' => '',
    ), $atts );

	$output = "<div class='cf_columns " . $atts["class"] . "'";

	$output .= ($atts["id"]) ? " id='" . $atts["id"] . "'>" : ">";

	$pattern = '/({[a-z]+})(.*)/i';


	preg_match_all($pattern, $content, $matches);

	$cols = array();

	$colvalues = array(
		"half" => (1/2),
		"third" => (1/3),
		"twothirds" => (2/3),
		"quarter" => (1/4),
		"threequarters" => (3/4)
		);

	var_dump($colvalues);
	$coltotal = 1;

	foreach ($matches[1] as $key => $chunk) {

		$columnid = str_replace("{", "", $chunk);
		$columnid = str_replace("}", "", $columnid);

		$coltotal = $coltotal - $colvalues[$columnid];
		
		$output .= "div class='col-" . $columnid . "' <BR />";
		$inner = wpautop( strstr($content, $chunk, true) );
		$output .= $inner . " /div ";


		// remaining content:
		$content = substr( strstr($content, $chunk), strlen($chunk) );
	}


	$output .= "Last column is " . ceil(100 * $colvalues["twothirds"]) . " % wide <BR />";
	
	$output .= wpautop( $content );	


	$output .= "</div>";
	return $output;

}

add_shortcode("columns", "cf_column_system");

remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 99);
add_filter( 'the_content', 'shortcode_unautop',100 );