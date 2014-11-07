<?php

/************* THUMBNAIL SIZE OPTIONS *************/

/* sizes needed 

 		gallery


 		gallery-thumb

*/

 $cropped_ratio = 1.5; // 6 x 4

 $max = 1000;
 $max_tablet = 900;
 $max_phone = 600;


$imagesizes = array( 



	// This approach creates a lot of assets, but I don't know if I mind this - it's more important to serve the correct sizes for devices
	// than to save our own server space, which is generally unlimited these days.  Do consider when there is an overlap

	// This does not consider retina displays yet.


	


	'gallery-thumb' => array( 300, ( / $cropped_ratio), true ), // same for everything, why not

	/* add mobile sizes */

	// add upper limit for default sizes

	'large-tablet' => array( $max_tablet, 	$max_tablet, 	false ), 
	'large-mobile' => array( $max_phone, 	$max_phone, 	false ), 

	// This may be a bad idea, but by setting a lot of images to be basically the same file, we encourage reuse of assets, and not a lot of sizes.
	'medium-tablet' => array( $max_tablet, 	$max_tablet, 	false ), 
	'medium-mobile' => array( $max_phone, 	$max_phone, 	false ), 

	
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

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {

	global $imagesizes;
	$tomerge = array();
	foreach ($imagesizes as $key => $imagesize) {
		if ($imagesize[2]) {
			$tomerge[$key] = __("Cropped to " . $imagesize[0] . 'px by ' . $imagesize[1] . 'px');
		} else {
			$tomerge[$key] = __("Uncropped, within " . $imagesize[0] . 'px by ' . $imagesize[1] . 'px');	
		}
		
	}
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