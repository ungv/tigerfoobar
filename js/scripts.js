$(document).ready(function() {
	$('.container h2').click(function() {
		$(this).siblings().slideToggle(200, function() {
			$origText = $(this).siblings().text().substring(0, $(this).siblings().text().length - 1);
			if ($(this).siblings().attr('class') == 'expanded') {
				$(this).siblings().text($origText + '-')
				$(this).siblings().attr('class', 'collapsed');
			} else {
				$(this).siblings().text($origText + '+')
				$(this).siblings().attr('class', 'expanded');
			}
		});
	});
});