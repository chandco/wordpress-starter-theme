<?php

function wp_gear_manager_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_enqueue_script('jquery');
}

function wp_gear_manager_admin_styles() {
wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts');
add_action('admin_print_styles', 'wp_gear_manager_admin_styles');


?>

<form id='mce-feature-box-form'>
	<label>
		Box Title
		<input type='text' name='mce-feature-box' id='mce-feature-box' />
	</label>

	<label>
		Box Text (Optional)
		<textarea id='mce-feature-content'></textarea>
	</label>

	<input type='submit' value='Update' />
</form>

<div class="uploader">
	<input id="_unique_name" name="settings[_unique_name]" type="text" />
	<input id="_unique_name_button" class="button" name="_unique_name_button" type="text" value="Upload" />
</div>


<script>
var dialogArguments = parent.tinymce.activeEditor.windowManager.getParams();


console.log(dialogArguments);
document.getElementById('mce-feature-box').value = dialogArguments.title;
document.getElementById('mce-feature-content').value = dialogArguments.content;

document.getElementById('mce-feature-box-form').addEventListener('submit', function(e) {
	e.preventDefault();
	
	parent.tinymce.activeEditor.plugins.features._process_popup_form(
		document.getElementById('mce-feature-box').value,
		document.getElementById('mce-feature-content').value,
		parent.tinymce.activeEditor.windowManager,
		dialogArguments.element
	);

})

jQuery = parent.jQuery;
wp = parent.wp;
wp.media.editor.open;
console.log(jQuery);
jQuery(document).ready(function($){
	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;
 
	$('.stag-metabox-table .button').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$("#"+id).val(attachment.url);
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}
 
		wp.media.editor.open(button);
		return false;
	});
 
	$('.add_media').on('click', function(){
		_custom_media = false;
	});
});
</script>

