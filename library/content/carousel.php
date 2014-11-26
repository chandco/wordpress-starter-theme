<?php




function display_attached_images_carousel($atts) {
	
	$atts = shortcode_atts(
		array(
			'tag' => false,
			'id' => false,
			'fill' => false,
		), $atts, 'images_carousel' );
	

	
	if ($atts["tag"]) {
		$args = "media_tags=" . $atts["tag"] . "&orderby=menu_order&order=DESC";
		$images = get_attachments_by_media_tags($args);
	} else {

		$imageArgs = array(
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'numberposts' => null,
			'post_status' => null,
			'orderby' => 'menu_order',
			'posts_per_page'=>-1
		);

		global $post; 
		$imageArgs['post_parent'] = ($atts["id"]) ? $atts["id"] : get_the_ID();
		$images = get_posts($imageArgs);
	}
		
// either get attached from post.  Attached from another post OR a certain media tag depending on ATTs
		
	//	$output .= "<div class='cycle-container'>";
		$cropwide = ($atts["fill"]) ? 'fill-width' : '';
		$output .= "<div class='" . $cropwide . " cycle-slideshow' data-cycle-fx='fade' >";

		//$output .= '<span id="cycle-prev" class="cycle-prev">&nbsp;</span>';
		//$output .= '<span id="cycle-next" class="cycle-next">&nbsp;</span>';
			
		$newsize = 'gallery-large';
		if (ISMOBILE) {
		$newsize = $newsize . "-mobile";
		} else if (ISTABLET) { 
			$newsize = $newsize . "-tablet";		
		}
	foreach ($images as $attachment) {
		
		$big_output = wp_get_attachment_image_src( $attachment->ID, $newsize );
		$big_output = current($big_output);
		// <span class='tooltip'>" . $newsize . "</span>
		$output .= "<div class='carousel-slide'><img data-lazy='" . $big_output . "' title='" . $attachment->post_excerpt . "' alt='" . $attachment->post_title . "' /></div>";
	
	}


	$output .= "</div>";

	return $output;
	
	
}

// returns all the attached images as a carousel.  We may wish to repurpose this later, but it's a quick way for now.
// It also uses the title of the page as a title, in the future we will want to make this an option perhaps, for now we just need a 
// quick way of making a carousel for attached images to replace layerslider.
add_shortcode( 'images_carousel', 'display_attached_images_carousel' );