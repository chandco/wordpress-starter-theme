<?php

/************* THUMBNAIL SIZE OPTIONS *************/


$imagesizes = array( 
	'gallery-large' => array( 700, 700, true ), 
	'gallery-thumbnail' => array( 700, 700, true ),

	/* add mobile sizes */

	'tablet-max' => array( 900, 900, false ),
	'mobile-max' => array( 600, 600, false ),

	);


// Thumbnail sizes

foreach ($imagesizes as $key => $imagesize) {
	add_image_size( $key, $imagesize[0], $imagesize[1], $imagesize[2] );
}



add_filter("post_thumbnail_size","responsive_conditional_size");
function responsive_conditional_size($size) {

	if (ISMOBILE) {

	switch ($size) 
	{
		case 'featured-image':
		case 'large':
		case 'full':
		case false:

		return 'mobile-large';
		break;

		default:
		return $size;
		break;
	}


	
	} elseif (ISTABLET) { 

		switch ($size) 
		{
			case 'featured-image':
			case 'large':
			case 'full':
			case false:

			return 'tablet-large';
			break;

			default:
			return $size;
			break;
		}

	} else {
		return $size;
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
		$tomerge[$key] = __($imagesize[0] . 'px by ' . $imagesize[1] . 'px');
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

