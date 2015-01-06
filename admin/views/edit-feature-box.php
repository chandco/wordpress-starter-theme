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


</script>