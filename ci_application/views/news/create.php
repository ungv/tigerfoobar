<h2>Create a news item</h2>

<?php //reports errors related to form validation ?>
<?= validation_errors(); ?>

<?php //form creation helper ?>
<?= form_open('news/create'); ?>

	<label for="title">Title</label> 
	<input type="input" name="title" /><br />

	<label for="text">Text</label>
	<textarea name="text"></textarea><br />
	
	<input type="submit" name="submit" value="Create news item" /> 

</form>
