	</body>
	<?php 
	/*Our Scripts*/
	foreach($jsFiles as $jsFile) { ?>
		<script src="<?= base_url() . 'js/' . $jsFile . '.js' ?>" type="text/javascript"></script>
	<?php } ?>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#signup').click(function() {
			$('#signupPopup').show(200);
		});
		
		$('.cancelButton').click(function() {
			$('#signupPopup').hide(200);
		});
		
		$('input[type=text]').each(function() {
			$(this).addClass('outfocus');
		});
		$('input[type=text]').focus(function() {
			$(this).attr('class', 'infocus');
		}).blur(function() {
			$(this).attr('class', 'outfocus');
		}).mouseenter(function() {
			$(this).addClass('hover');
		}).mouseleave(function() {
			$(this).removeClass('hover');
		});
	
		var projects = [
			{
				value: "jquery",
				label: "jQuery",
				desc: "the write less, do more, JavaScript library",
				icon: "jquery_32x32.png"
			},
			{
				value: "jquery-ui",
				label: "jQuery UI",
				desc: "the official user interface library for jQuery",
				icon: "jqueryui_32x32.png"
			},
			{
				value: "sizzlejs",
				label: "Sizzle JS",
				desc: "a pure-JavaScript CSS selector engine",
				icon: "sizzlejs_32x32.png"
			}
		];

		$( "#tags" ).autocomplete({
			minLength: 0,
			source: projects,
			focus: function( event, ui ) {
				$( "#tags" ).val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				$( "#tags" ).val( ui.item.label );
				return false;
			}
		})
		.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li>" )
			.append( "<a>" + item.label + "<br>" + item.desc + "</a>" )
			.appendTo( ul );
		};
	});
	</script>
</html>