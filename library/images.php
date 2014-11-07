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

	'feed-image' => array( $max_phone, 	($max_phone / $cropped_ratio), 		true ) // duplicates mobile for now, but this is okay, that generally means it's a reusable image, without changing theme code

	);


// Thumbnail sizes

foreach ($imagesizes as $key => $imagesize) {
	add_image_size( $key, $imagesize[0], $imagesize[1], $imagesize[2] );
}



/* Change the add media editor, to insert a <picture> if the criteria is right.  

This is flawed in that it may encourage content editors to not use it, which is bad, so we may need to remove the defaults somehow or force this on thumb/medium/large

*/

// only add a responsive image if they asked for one

function create_picture_element($id, $images, $caption, $title) {


	    $html = '<picture>';
		$html .= '<!--[if IE 9]><video style="display: none;"><![endif]-->"';


		$img_full = wp_get_attachment_image_src($id, $images["large"]["name"]);
		$img_tablet = wp_get_attachment_image_src($id, $images["medium"]["name"]);
		$img_mobile = wp_get_attachment_image_src($id, $images["small"]["name"]);


		$srcset =  '<source srcset ="' . $img_full[0] . '" media="(min-width: ' . $images["large"]["size"] . 'px)">';
		$srcset .= '<source srcset ="' . $img_tablet[0] . '" media="(min-width: ' . $images["medium"]["size"] . 'px)">';
		$srcset .= '<source srcset ="' . $img_mobile[0] . '" media="(min-width: ' . $images["small"]["size"] . 'px)">';


		$html .= '<!--[if IE 9]></video><![endif]-->';
		$html .= $srcset;
		$html .= '<img srcset="' . $img_tablet[0] . '" alt="' . $caption . '" title="' . $title . '">';
		$html .= '</picture>';

		return $html;

	
}


function responsive_editor_filter($html, $id, $caption, $title, $align, $url, $size) {
    
    // this should probably be a bit more dynamic but then it's linked to the editor add sizes below so, it's okay for now.
    if ($size == "gallery-large" || $size == "featured-image") {
    	
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
						"name" => $size,
						"size" => $imagesizes[$size . "-mobile"][1]
						),
					);

	    return create_picture_element($id, $images, $caption, $title);


    } else if ($size == "large" || $size == "medium" || $size == "thumbnail") {

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

    	return create_picture_element($id, $images, $caption, $title);

    } else {
    	return $html;
    }
}
add_filter('image_send_to_editor', 'responsive_editor_filter', 10, 9);



// We may not want this, but it's going to kick in for the gallery, which won't be using picture fill.
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

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

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