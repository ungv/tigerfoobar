	</body>
	<?php 
	/*Our Scripts*/
	foreach($jsFiles as $jsFile) { ?>
		<script src="<?= base_url() . 'js/' . $jsFile . '.js' ?>" type="text/javascript"></script>
	<?php } ?>
</html>