$(document).ready(function() {
	$('#urlSubmit').show();
	$('#urlSubmit').removeClass('popup');
	$('#urlSubmit h3').hide();
	$input = $('<input>').attr('class', 'full outfocus').attr('type', 'text').attr('placeholder', 'URL*').attr('name', 'pasteURL');
	$('#first').after($input);
});