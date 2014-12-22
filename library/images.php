<?php

/************* THUMBNAIL SIZE OPTIONS *************/

/* sizes needed 

 		gallery


 		gallery-thumb

*/


## Configure your sizes here.  This generally sets the media queries and image sizes to go with it.

 // you'll want to sync this with your LESS variables.

 $cropped_ratio = 1.5; // 6 x 4

 $max = 981;
 $max_tablet = 768; // making these up a bit
 $max_phone = 580;

$mini = $max_phone / 2;



/*
$tomerge['gallery-large'] = __("Uncropped Responsive Image");
	$tomerge['featured-image'] = __("Cropped*/


$imagesizes = array( 

	// This approach creates a lot of assets, but I don't know if I mind this - it's more important to serve the correct sizes for devices
	// than to save our own server space, which is generally unlimited these days.  Do consider when there is an overlap

	// This does not consider retina displays yet.

	// it also relies heavily on featured-image and gallery-large



	'gallery-thumb' => array( 300, (300 / $cropped_ratio), true ), // same for everything, why not

	/* add mobile sizes */

	// add upper limit for default sizes

	'large-tablet' => array( $max_tablet, 	$max_tablet, 	false ), 
	'large-mobile' => array( $max_phone, 	$max_phone, 	false ), 

	// This may be a bad idea, but by setting a lot of images to be basically the same file, we encourage reuse of assets, and not a lot of sizes.
	'medium-tablet' => array( $max_tablet, 	$max_tablet, 	false ), 
	'medium-mobile' => array( $max_phone, 	$max_phone, 	false ), 

	
	## DO NOT REMOVE THESE WITHOUT RECONFIGURING THINGS BELOW ##

	/*
		It should really be such that it is more dynamic and less hard coded to these two names, and a trio of sizes, 
		but this up for adaptation later and should serve most situations
	*/

	'featured-image' => 		array( $max, 	($max / $cropped_ratio), 	true ), 
	'featured-image-tablet' => 	array( $max_tablet, 	($max_tablet / $cropped_ratio), 	true ), 
	'featured-image-mobile' => 	array( $max_phone, 	($max_phone / $cropped_ratio), 		true ), 

	'gallery-large' => 			array( $max, 	$max, 	false ), 
	'gallery-large-tablet' => 	array( $max_tablet, 	$max_tablet, 	false ), 
	'gallery-large-mobile' => 	array( $max_phone, 	$max_phone, 	false ), 

	'feed-image' => array( $mini , 	($mini / $cropped_ratio), 		true ), // duplicates mobile for now, but this is okay, that generally means it's a reusable image, without changing theme code
	'feed-image-tablet' => array( $mini, 	($mini / $cropped_ratio), 		true ), // duplicates mobile for now, but this is okay, that generally means it's a reusable image, without changing theme code
	'feed-image-mobile' => array( $mini, 	($mini / $cropped_ratio), 		true ), // duplicates mobile for now, but this is okay, that generally means it's a reusable image, without changing theme code

	);


// Thumbnail sizes

foreach ($imagesizes as $key => $imagesize) {
	add_image_size( $key, $imagesize[0], $imagesize[1], $imagesize[2] );
}



/* Change the add media editor, to insert a <picture> if the criteria is right.  

This is flawed in that it may encourage content editors to not use it, which is bad, so we may need to remove the defaults somehow or force this on thumb/medium/large

*/

// only add a responsive image if they asked for one


// prepare and return the actual final responsive image.  This should meet the HTML spec but could be changed if we don't use picture srcset in the future.
// There is a polyfill in the JS to work this, otherwise this is going to break on a lot of browsers as of Jan 2015
function create_picture_element($id, $images, $caption, $title, $align, $html) {


	    $html = '<picture class="align-' . $align . '">';
		$html .= '<!--[if IE 9]><video style="display: none;"><![endif]-->';

		global $max, $max_tablet, $max_phone;

		$img_full = wp_get_attachment_image_src($id, $images["large"]["name"]);
		$img_tablet = wp_get_attachment_image_src($id, $images["medium"]["name"]);
		$img_mobile = wp_get_attachment_image_src($id, $images["small"]["name"]);

		$srcset = "";
		$srcset .= '<source srcset ="' . $img_mobile[0] . '" media="(max-width: ' . $max_phone . 'px)">';
		$srcset .= '<source srcset ="' . $img_tablet[0] . '" media="(max-width: ' . $max_tablet . 'px)">';
		$srcset .= '<source srcset ="' . $img_full[0] . '" media="(min-width: ' . $max_tablet . 'px)">'; // anything over basically.
		
		


		$html .= '<!--[if IE 9]></video><![endif]-->';
		$html .= $srcset;
		$html .= '<img srcset="' . $img_tablet[0] . '" alt="' . $caption . '" title="' . $title . '">';
		$html .= '</picture>';

		return $html;

	
}


// work out what images we can output.  For ease, we're only going with 3 images.
function get_image_src_list($size) {

	if ($size == "large" || $size == "medium" || $size == "thumbnail") {

    	global $max, $max_phone, $max_tablet;
    	// these will always exist in wordpress
		$images = array(
					"large" => array(
						"name" => "large",
						"size" => $max
						),
					"medium" => array(
						"name" => "medium",
						"size" => $max_tablet
						),
					"small" => array(
						"name" => "thumbnail",
						"size" => $max_phone
						),
					);
	} else {
		global $imagesizes;
		$images = array(
					"large" => array(
						"name" => $size,
						"size" => $imagesizes[$size][1]
						),
					"medium" => array(
						"name" => $size . "-tablet",
						"size" => $imagesizes[$size . "-tablet"][1]
						),
					"small" => array(
						"name" => $size . "-mobile",
						"size" => $imagesizes[$size . "-mobile"][1]
						),
					);
	}
	
	return $images;
}




// to be used instead of get_the_thumbnail.  However we haven't been able to make the filter work with this so it's a manual call in teh templates.
function responsive_image_thumbnail( $post_id = null, $size = 'featured-image', $attr = '' ) {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
	$post_thumbnail_id = get_post_thumbnail_id( $post_id );

	if ( $post_thumbnail_id ) {

		$images = get_image_src_list($size);

		return create_picture_element($post_thumbnail_id, $images, "","", "", "");

	} else {
		return '';
	}
	
}



// Change a shortcode into a responsive image.  This should stop the 
function responsive_image_shortcode($atts) {

	$images = get_image_src_list($atts["size"]);
	return create_picture_element($atts["id"], $images, $atts["caption"], $atts["title"], $atts["align"], ""); // no html

}
add_shortcode( 'responsive_image', 'responsive_image_shortcode' );


// create a shortcode for the responsive image.  We'll be sending that to the editor instead of the original one.
function create_picture_shortcode($id, $size, $caption, $title, $html, $align) {

	return "[responsive_image id='" . $id . "' size='" . $size . "' caption='" . $caption . "' title='" . $title . "' align='" . $align . "']"; 

	// .  $html . "[/responsive_image]";
	// we could wrap in the original HTML but we won't as this can break and stops people understanding that the image might get ditched.  Make them put in a new shortcode.
}


// Send a shortcode to the editor IF they picked a size which is a responsive set.
function responsive_editor_filter($html, $id, $caption, $title, $align, $url, $size) {
    
    // this should probably be a bit more dynamic but then it's linked to the editor add sizes below so, it's okay for now.
    if ($size == "gallery-large" || $size == "featured-image") {
	    return create_picture_shortcode($id, $size, $caption, $title, $html, $align);
    } else {
    	return $html;
    }
}
add_filter('image_send_to_editor', 'responsive_editor_filter', 10, 9);



// We may not want this, but it's going to kick in for the gallery, which won't be using picture fill.
// This is a server side replacing of the images.  We can only do this because it's a shortcode.
// We might want to do this for the shortcode of responsive images but the serverside PHP is 
// less effective than client side media queries
add_filter("post_thumbnail_size","responsive_conditional_size");
function responsive_conditional_size($size) {

	if (ISMOBILE) {
		$newsize = $size . "-mobile";
	} else if (ISTABLET) { 
		$newsize = $size . "-tablet";		
	} else {
		// no change needed, we're on a desktop m9s
		return $size;
	}

	// if we got this far then work out a new size
	global $imagesizes;

	if (isset($imagesizes[$newsize])) {

		return $newsize;
	} else {
		return $size; // if we didn't define it we can't ask for it, just give the original
	}
}



// add the new sizes to the WP image insert thing.

add_filter( 'image_size_names_choose', 'cf_custom_image_sizes' );

function cf_custom_image_sizes( $sizes ) {

	//global $imagesizes;
	$tomerge = array();
	$tomerge['gallery-large'] = __("Uncropped Responsive Image");
	$tomerge['featured-image'] = __("Cropped Responsive Image");	
	
    return array_merge( $sizes, $tomerge );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

// Now fix the gallery:
require_once("gallery.php");