	


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

		// $('#scoreControl').buttonset();

		// // Kudos Colors       -3         -2         -1         0          +1         +2         +3
		// var kudosColors = ['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64'];

		// for (var i = 0; i <= 6; i++) {
		// 	$("#scoreControl label").eq(i).css("background-color", kudosColors[i]);
		// 	$("#scoreControl label").eq(i).css("background-image", "none");
		// 	$("#scoreControl label").eq(i).css("border-radius", 0);
		// 	$("#scoreControl label").eq(i).css("opacity", 0.8);
		// }
		
		$('.container h2').mouseover(function() {
			$(this).attr('style', 'background-color: rgb(233, 233, 233)');
		}).mouseout(function() {
			$(this).attr('style', 'background-color: white');
		});
	});