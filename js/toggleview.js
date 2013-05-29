$(document).ready(function() {
	$('#toggleMap').addClass('selected');

	$('#toggleMap').click(function() {
		$('#treemapCanvas').show(200);
		$('#listview').hide(200);
		$('#toggleList').removeClass('selected');
		$('#toggleMap').addClass('selected');
	});

	$('#toggleList').click(function() {
		$('#listview').show(200);
		$('#treemapCanvas').hide(200);
		$('#toggleMap').removeClass('selected');
		$('#toggleList').addClass('selected');
	});
});
