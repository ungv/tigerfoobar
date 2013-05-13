	</body>
	<?php 
	/*Our Scripts*/
	foreach($jsFiles as $jsFile) { ?>
		<script src="<?= base_url() . 'js/' . $jsFile . '.js' ?>" type="text/javascript"></script>
	<?php } ?>
	<script type="text/javascript">
	$(function() {
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