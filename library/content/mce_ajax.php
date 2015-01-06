<?php

add_action('wp_ajax_custom_mce', 'wp_custom_mce');

function wp_custom_mce() {
	include("edit-feature-box.php");
}



?>